<?php

namespace Furesz\Date;


class Validate {

    /**
     * @param \DateInterval $subject
     * @param \DateInterval $min
     * @param \DateInterval $max
     *
     * @return bool
     */
    public static function betweenInterval(\DateInterval $subject, \DateInterval $min, \DateInterval $max)
    {
        //@TODO DateInterval not comparable if PHP5<5.6
        if (
        (
            (new \DateTime())->add($subject) > (new \DateTime())->add($min)
            && (new \DateTime())->add($subject) < (new \DateTime())->add($max)
        )
        ) {
            return true;
        }

        return false;
    }
}