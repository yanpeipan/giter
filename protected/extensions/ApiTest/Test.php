<?php
/**
 * api单元测试
 */
class Test extends ApiTestBase
{
    public function run()
    {
        $params = self::getApis();
        return self::testApi($params);
    }
    
    /**
     * @param $params
     */
    protected function testApi($params)
    {
        //循环
        $result = array();
        foreach($params as $key=>$val)
        {
            $method = $key;
            $data   = self::getData($val);
            $result[$key] = self::chkFieldExists($method,$data);
        }
        return $result;
    }
    
}



?> 