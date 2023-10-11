<?php

namespace Router;

abstract class AbstractRouter
{
    abstract public function route(): void;
    public function redirect($to): void
    {
        header("Location: $to");
    }
    public static function getUrl(): array
    {
        // explode the url-address by slash sign
        /**
         * admin/office/recovery?recovery_code=$code"
         *
         * $parts = [website.domain.name, admin, office, recovery?recovery_code=$code]
         */
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $size = count($urlParts);

        // get the last item of the given array of url-address exploded by slash
        // to tack if there is GET request or not
        $lastPart = $urlParts[$size - 1];

        /**
         * admin/office/recovery?recovery_code=$code"
         *
         * $lastPart = [recovery, recovery_code=$code]
         */
        $lastPartArray = explode('?', $lastPart);

        $lastPartSize = count($lastPartArray);
        // $lastPart = [recovery] -> no GET request
//        if ($lastPartSize === 1) {
//            return $urlParts;
//        } else {
//            /**
//             * admin/office/recovery?recovery_code=$code"
//             *
//             * $lastPart = [recovery, recovery_code=$code]
//             */
//            if ($lastPartSize === 2) {
//                /**
//                 * $urlPart = 'recovery'
//                 * $getString = 'recovery_code=$code'
//                 */
//                $urlPart = $lastPartArray[0];
//                $getString = $lastPartArray[1];
//
//                /**
//                 * $getStringExplodedByAnd = [recovery_code=$code]
//                 */
//                $getStringExplodedByAnd = explode('&', $getString);
//                $resultGet = [];
//                foreach ($getStringExplodedByAnd as $item) {
//                    /**
//                     * $getStringExplodedByEqualSign = [recovery_code, $code]
//                     */
//                    $getStringExplodedByEqualSign = explode('=', $item);
//                    if (count($getStringExplodedByEqualSign) === 2) {
//                        $urlParts[$size - 1] = $urlPart;
//                        $resultGet += [
//                            /**
//                             * recovery_code => $code
//                             */
//                            $getStringExplodedByEqualSign[0] => $getStringExplodedByEqualSign[1]
//                        ];
//                    }
//                }
//                $urlParts += [
//                    'get' => $resultGet
//                ];
//            }
//        }
        return $urlParts;
    }
}