<?php


namespace Ace;


class System
{
    /**
     * 查看CPU使用率 Linux.
     *
     * @return string CPU使用率
     */
    static function getUsageOfLinuxCPU()
    {
        $step1 = self::getLinuxCPU();
        sleep(1);
        $step2 = self::getLinuxCPU();

        $time = $step2['time'] - $step1['time'];
        $total = $step2['total'] - $step1['total'];
        $usage = bcdiv($time, $total, 3);
        return $usage;
    }

    /**
     * 查看CPU当前时刻的状态 Linux.
     *
     * @return array 状态
     */
    static function getLinuxCPU()
    {
        $mode = "/(cpu)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)/";
        $string = shell_exec("more /proc/stat");
        preg_match_all($mode, $string, $arr);
        $total = $arr[2][0] + $arr[3][0] + $arr[4][0] + $arr[5][0] + $arr[6][0] + $arr[7][0] + $arr[8][0] + $arr[9][0];
        $time = $arr[2][0] + $arr[3][0] + $arr[4][0] + $arr[6][0] + $arr[7][0] + $arr[8][0] + $arr[9][0];
        $return['total'] = $total;
        $return['time'] = $time;
        return $return;
    }

    /**
     * 查看内存使用率 Linux.
     *
     * @return int|string 使用率
     */
    static function getUsageOfLinuxMemory()
    {
        $str = shell_exec("more /proc/meminfo");
        $mode = "/(.+):\s*([0-9]+)/";
        preg_match_all($mode, $str, $arr);
        $usage = bcdiv($arr[2][1], $arr[2][0], 3);
        return 1 - $usage;
    }
}