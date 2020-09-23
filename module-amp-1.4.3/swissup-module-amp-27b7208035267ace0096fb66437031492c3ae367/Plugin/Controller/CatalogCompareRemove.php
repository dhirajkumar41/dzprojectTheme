<?php
namespace Swissup\Amp\Plugin\Controller;

class CatalogCompareRemove extends AbstractPlugin
{
    /**
     * Modify remove from compare response
     * @param  \Magento\Catalog\Controller\Product\Compare\Remove $subject
     * @param  mixed $originalResult
     * @return bool
     */
    public function afterExecute(
        \Magento\Catalog\Controller\Product\Compare\Remove $subject,
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
        if (!$redirectTo) {
            $redirectTo = $this->helper->getAmpUrl();
        }

        $result = [
            'success'  => true,
            'messages' => []
        ];

        if ($this->messageHelper->hasFailureMessages()) {
            $result['success']  = false;
            $result['messages'] = $this->messageHelper->getMessages(true, true, true);
        } else {
            if ($redirectTo) {
                $redirectTo = str_replace('http://', 'https://', $redirectTo);
                $response->setHeader('AMP-Redirect-To', $redirectTo);
            }
            $product = $this->getProductById($request->getParam('product'));
            $result['messages']['success'] = [
                __('You removed product %1 from the comparison list.', $product->getName())
            ];
        }

        return $this->helper->successfulResponse(
            $response, $origin, $result, $result['success'] ? 200 : 400
        );
    }
}
