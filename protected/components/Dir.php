<?php
class Dir{
    /**
     * 递归创建文件夹,使用前请切换到目标文件夹
     * @param string $pathname 文件相对路径
     * @param string $mode
     * @return boolean
     */
    public static function mkdirRecursive($pathname, $mode=0777)
    {
        if(is_dir($pathname))
        {
            return TRUE;
        }

        $parts = explode(DIRECTORY_SEPARATOR, $pathname);
        $current = '';

        foreach ($parts as $part)
        {
            $current .= $part.DIRECTORY_SEPARATOR;
            $realpath = realpath($current);

            if(!is_dir($current) && !@mkdir($current, 0777))
            {
                return FALSE;
            }
        }

        return TRUE;
    }
}
?>