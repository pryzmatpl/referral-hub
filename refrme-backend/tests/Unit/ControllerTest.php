<?php
declare(strict_types=1);

namespace Tests\Unit;
use Codeception\Test\Unit;
use PHPUnit\Framework\TestCase;
use App\Controllers\Controller;

final class ControllerTest extends Unit
{
  //Testing the hashing scheme for iwahash with base64_encode
  public function testIwahash()
  {
    $originp = 'prizm';
    $this->assertEquals('cHJpem1+TkFNRTpQSU9UUg==',
                        Controller::iwahash($originp,'NAME','PIOTR'));
  }

  public function testIwadehash()
  {
    $dat='';
    $container='';
    
    $nucon = new Controller($container);
    $this->assertEquals('NAME:PIOTR',
			$nucon->iwadehash('cHJpem1+TkFNRTpQSU9UUg==',$dat));
  }
  
  public function testIwadehashLonger()
  {
    $dat='';
    $container='';
    
    $nucon = new Controller($container);
    $this->assertEquals('NAME:PIOTR~SURNAME:SLUPSKI~BIRTH:19.04.1989~EMAIL:piotroxp@gmail.com',
			$nucon->iwadehash('cHJpem1+TkFNRTpQSU9UUn5TVVJOQU1FOlNMVVBTS0l+QklSVEg6MTkuMDQuMTk4OX5FTUFJTDpwaW90cm94cEBnbWFpbC5jb20=',$dat));
  }

  public function testIwahashLonger()
  {
    $dat='prizm';
    $container='';
    
    $nucon = new Controller($container);
    $hash = $nucon->iwahash($dat,'NAME','PIOTR');
    $hash = $nucon->iwahash($hash,'SURNAME','SLUPSKI');
    $hash = $nucon->iwahash($hash,'BIRTH','19.04.1989');
    $hash = $nucon->iwahash($hash,'EMAIL','piotroxp@gmail.com');
    
    $this->assertEquals($hash,
			'V1RCb1MyTkhWblJOVTNSVllUQmFUMVZzVW5kVlZrNVdUMVpXVmxwNk1EbG1iRTVXVldzMVFsUlZWVFpWTUhoV1ZVWk9URk5SUFQxK1FrbFNWRWc2TVRrdU1EUXVNVGs0T1E9PX5FTUFJTDpwaW90cm94cCU0MGdtYWlsLmNvbQ==');
  }
    
  public function testIwahashEmpty()
  {
    $dat='';
    $container='';

    $this->expectException(Exception::class);
    $nucon = new Controller($container);
    $nucon->iwahash($dat,'TEST','TEST');
  }

  public function testIwahashDataEmpty()
  {
    $dat='';
    $container='';

    $nucon = new Controller($container);
    $res = $nucon->iwahash('prizm','','');

    $this->assertEquals('prizm',
			$res);
  }

  public function testOrigin(){
    $container='';
    $nucon = new Controller($container);
    $origin = $nucon->origin('cHJpem1+TkFNRTpQSU9UUn5TVVJOQU1FOlNMVVBTS0l+QklSVEg6MTkuMDQuMTk4OX5FTUFJTDpwaW90cm94cEBnbWFpbC5jb20=');
    $orig ='prizm';
    $this->assertEquals($orig,
			$origin);
  }

  public function testCleandata(){
    $container='';
    $nucon = new Controller($container);
    $origin = $nucon->cleandata('cHJpem1+TkFNRTpQSU9UUn5TVVJOQU1FOlNMVVBTS0l+QklSVEg6MTkuMDQuMTk4OX5FTUFJTDpwaW90cm94cEBnbWFpbC5jb20=');
    $orig ='NAME:PIOTR~SURNAME:SLUPSKI~BIRTH:19.04.1989~EMAIL:piotroxp@gmail.com';
    $this->assertEquals($orig,
			$origin);
  }

  public function testTraverse(){
    $container='';
    $nucon = new Controller($container);
    $elemes = $nucon->iwadehash('cHJpem1+TkFNRTpQSU9UUn5TVVJOQU1FOlNMVVBTS0l+QklSVEg6MTkuMDQuMTk4OX5FTUFJTDpwaW90cm94cEBnbWFpbC5jb20=');
    $elemes = explode('~',$elemes);
    $origin = $nucon->traverse($elemes,'NAME');
    $orig ='PIOTR';
    $this->assertEquals($orig,
			$origin);
  }

  public function testSpawn(){
    $container='';
    $nucon = new Controller($container);
    $elemes = json_decode($nucon->spawn('cHJpem1+TkFNRTpQSU9UUn5TVVJOQU1FOlNMVVBTS0l+QklSVEg6MTkuMDQuMTk4OX5FTUFJTDpwaW90cm94cEBnbWFpbC5jb20='),true);
    $orig ='PIOTR';
    print_r($elemes);
    $this->assertEquals($orig,
			$elemes[0]['NAME']);
  }

  
}
