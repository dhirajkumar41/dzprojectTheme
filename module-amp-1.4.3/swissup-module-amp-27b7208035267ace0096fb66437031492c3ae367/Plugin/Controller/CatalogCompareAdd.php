<?php
namespace Swissup\Amp\Plugin\Controller;

class CatalogCompareAdd extends AbstractPlugin
{
    /**
     * Modify add to compare response
     * @param  \Magento\Catalog\Controller\Product\Compare\Add $subject
     * @param  mixed $originalResult
     * @return bool
     */
    public function afterExecute(
        \Magento\Catalog\Controller\Product\Compare\Add $subject,
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

        $result = [
            'success'  => true,
            'messages' => []
        ];

        if ($this->messageHelper->hasFailureMessages()) {
            $result['success']  = false;
            $result['messages'] = $this->messageHelper->getMessages(true, true, true);
        } else {
            $this->messageHelper->getPageMessages();
            $product = $this->getProductById($request->getParam('product'));
            $result['messages']['success'] = [
                __('You added %1 to the comparison list.', $product->getName())
            ];
        }

        return $this->helper->successfulResponse(
            $response, $origin, $result, $result['success'] ? 200 : 400
        );
    }
}
