<?php

namespace WidgetTest;

class RequestTest extends TestCase
{
    public function testInvoke()
    {
        $widget = $this->object;

        $name = $widget->request('name');
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

        $this->assertEquals($name, $source);

        $default = 'default';
        $name2 = $widget->request('name', $default);
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : $default;

        $this->assertEquals($name2, $default);
    }

    public function testSet()
    {
        $widget = $this->object;

        $widget->set('key', 'value');

        $this->assertEquals('value', $widget->request('key'), 'string param');

        $widget->fromArray(array(
            'key1' => 'value1',
            'key2' => 'value2',
        ));

        $this->assertEquals('value2', $widget->request('key2'), 'array param');
    }

    public function testRemove()
    {
        $widget = $this->object;

        $widget->set('remove', 'just a moment');

        $this->assertEquals('just a moment', $widget->request('remove'));

        $widget->remove('remove');

        $this->assertNull($widget->request('remove'));
    }
    
    public function testMethod()
    {
        foreach (array('GET', 'POST') as $method) {
            $this->widget->remove('request');
            $this->widget->remove('server');
            $request = new \Widget\Request(array(
                'widget' => $this->widget,
                'fromGlobal' => false,
                'servers' => array(
                    'REQUEST_METHOD' => $method
                )
            ));
            $this->widget->set('request', $request);
            
            $this->assertTrue($request->{'in' . $method}());
            $this->assertTrue($request->inMethod($method));

            $method = strtolower($method);
            $method[0] = strtoupper($method[0]);
            $this->{'in' . $method}->setOption('request', $request);
            $this->assertTrue($this->{'in' . $method}());
        }
        
        $this->request->setMethod('PUT');
        $this->assertTrue($this->request->inMethod('PUT'));
    }
    
    public function testAjax()
    {
        $this->server->set('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');
        
        $this->assertTrue($this->inAjax());
        
        $this->server->set('HTTP_X_REQUESTED_WITH', 'json');
        
        $this->assertFalse($this->inAjax());
        
        $this->server->remove('HTTP_X_REQUESTED_WITH');
        
        $this->assertFalse($this->inAjax());
    }
    
    /**
     * @expectedException \Widget\Exception\InvalidArgumentException
     */
    public function testInvalidParameterReference()
    {
        $this->request->getParameterReference('exception');
    }
    
    public function testGetIp()
    {
        $this->server->set('HTTP_X_FORWARDED_FOR', '1.2.3.4');
        $this->assertEquals('1.2.3.4', $this->request->getIp());
        
        $this->server->set('HTTP_X_FORWARDED_FOR', '1.2.3.4, 2.3.4.5');
        $this->assertEquals('1.2.3.4', $this->request->getIp());
        
        $this->server->remove('HTTP_X_FORWARDED_FOR');
        $this->server->set('HTTP_CLIENT_IP', '8.8.8.8');
        $this->assertEquals('8.8.8.8', $this->request->getIp());
        
        $this->server->remove('HTTP_CLIENT_IP');
        $this->server->set('REMOTE_ADDR', '9.9.9.9');
        $this->assertEquals('9.9.9.9', $this->request->getIp());
        
        $this->server->set('HTTP_X_FORWARDED_FOR', 'invalid ip');
        $this->assertEquals('0.0.0.0', $this->request->getIp());
    }
}