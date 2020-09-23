<?php
namespace Swissup\Amp\Plugin\Controller;

class CheckoutCartAdd extends AbstractPlugin
{
    /**
     * Modify add to cart response
     * @param  \Magento\Checkout\Controller\Cart\Add $subject
     * @param  mixed $originalResult
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        \Magento\Checkout\Controller\Cart\Add $subject,
        $originalResult
    ) {
        $request = $subject->getRequest();
        if (!$request->isPost() || !$request->getQuery('amp')) {
            return $originalResult;
        }

        $response = $subject->getResponse();
        if (!$origin = $this->helper->validateCorsRequest($request)) {
            return $this->helper->unauthorizedResponse($response);
        }

        $redirectTo = $this->helper->getRedirectTo($response);
        if (!$redirectTo && $this->helper->shouldRedirectToCart()) {
            $redirectTo = $this->helper->getCartUrl();
        }

        $result = [
            'success'  => true,
            'messages' => []
        ];

        if ($this->messageHelper->hasFailureMessages()) {
            $result['success']  = false;
            $result['messages'] = $this->messageHelper->getMessages(true, true, true);
        } else {
            $this->messageHelper->getPageMessages();
            if ($redirectTo) {
                $response->setHeader('AMP-Redirect-To', $redirectTo);
            }
            $product = $this->getProductById($request->getParam('product'));
            $result['messages']['success'] = [
                __('You added %1 to your shopping cart.', $product->getName())
            ];
        }

        return $this->helper->successfulResponse(
            $response, $origin, $result, $result['success'] ? 200 : 400
        );
    }
}
