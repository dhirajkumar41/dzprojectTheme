<?php
namespace Swissup\Amp\Plugin\Controller;

use Magento\Framework\App\ActionInterface;

class WishlistIndexAdd extends AbstractPlugin
{
    /**
     * Modify wishlist dispatch
     *
     * @param \Magento\Wishlist\Controller\Index\Add $subject
     * @param \Magento\Framework\App\RequestInterface $request
     * @return void
     */
    public function beforeDispatch(
        \Magento\Wishlist\Controller\Index\Add $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if ($request->isPost() && $request->getQuery('amp') &&
            $subject->getActionFlag()->get('', ActionInterface::FLAG_NO_DISPATCH)
        ) {
            $subject->getActionFlag()->set('', ActionInterface::FLAG_NO_DISPATCH, false);
        }
    }

    /**
     * Modify add to wishlist response
     * @param  \Magento\Wishlist\Controller\Index\Add $subject
     * @param  mixed $originalResult
     * @return bool
     */
    public function afterExecute(
        \Magento\Wishlist\Controller\Index\Add $subject,
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

        if ($redirectTo = $this->helper->getRedirectTo($response)) {
            $response->setHeader('AMP-Redirect-To', $redirectTo);
        }

        $result = [
            'success'  => true,
            'messages' => []
        ];

        if ($this->messageHelper->hasFailureMessages()) {
            $result['success']  = false;
            $result['messages'] = $this->messageHelper->getMessages(false, true, true);
        } else {
            $this->messageHelper->getPageMessages();
            $product = $this->getProductById($request->getParam('product'));
            $result['messages']['success'] = [
                __('%1 has been added to your Wish List.', $product->getName())
            ];
        }

        return $this->helper->successfulResponse(
            $response, $origin, $result, $result['success'] ? 200 : 400
        );
    }
}
