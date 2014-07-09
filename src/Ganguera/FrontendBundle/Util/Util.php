<?php
namespace Ganguera\FrontendBundle\Util;

/**
 *
 * @author yandry
 */
class Util {
    
    public function getParts($list, $size_parts) {
        $result = array();

        for ($i = 0, $N = count($list); $i < $N; $i+= $size_parts) {
            $count = 0;
            $tmp_list = array();
            while ($count < $size_parts && $count + $i < $N) {
                $tmp_list[] = $list[$count + $i];
                $count++;
            }
            $result[] = $tmp_list;
        }
        return $result;
    }
}

?>
