<?php
class ApiTest extends ApiTestBase
{
    public function run()
    {
        $params = self::getApis();
        print_r($params);exit;
        $this->testApi($params);
    }
    
    /**
     * 
     * @param $params
     */
    public function testApi($params)
    {
        //循环
        foreach($params as $key=>$val)
        {
            
        }
    }
    
}



?> 