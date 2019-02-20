<?php
$DARK_BLUE = "\x1b[38;5;19m";
$GOLD = "\x1b[38;5;214m";
$GREEN = "\x1b[38;5;83m";
$RESET = "\x1b[m";
echo(' '.$GOLD.'____        _
| __ )  ___ | |_
|  _ \ / _ \| __|
| |_) | (_) | |_
|____/ \___/ \__|');
echo(' 
'.$DARK_BLUE.'['.date('H:i:s').'] '.$GOLD.'| '.$GREEN.'BOON');
echo('
 '.$DARK_BLUE.'['.date('H:i:s').'] '.$GOLD.'| '.$GREEN.'Creator - https://vk.com/normalfir');
echo('
 '.$DARK_BLUE.'['.date('H:i:s').'] '.$GOLD.'| '.$GREEN.'Log: '.$RESET.' 
');
ini_set('default_socket_timeout', 900);
$bot = new Bot();
while(true){
  $bot->bot();
}
class Bot{
  public $cd = [];
  function __construct(){
    $this->token = '4738ebb53cc541755d4fa79ebf8802daf6d8fe4bd3f110852f6838ee7e2e491f4d2166df0b07d97b28ed5';
    $this->id = 178083150;
    $this->newServer();
  }
  public function newServer(){
    $s = $this->decode('https://api.vk.com/method/groups.getLongPollServer?access_token='.$this->token.'&v=5.92&group_id='.$this->id)['response'];
    $this->server = $s['server']."?act=a_check&key=".$s['key']."&wait=90&mode=2&version=3&ts=";
    $this->ts = $s['ts'];
  }
  public function sendMessage($peer_id, $message) {
            $msg = 'https://api.vk.com/method/messages.send?';
            $params = array(
               'access_token' => $this->token,
               'v' => 5.92,
               'peer_id' => $peer_id,
               'message' => $message,
               'random_id' => rand(),
               'payload' => 1000
             );
            $met = $msg . http_build_query($params);
            $ch = curl_init($met);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result, true);
        }
  public function bot(){
    $data = $this->decode($this->server.$this->ts);
    if(!isset($data['ts'])){
      $this->newServer(); return;
    }
    $this->ts = $data['ts']; if(!isset($data['updates'][0])) return;
    foreach($data['updates'] as $d){
      $obj = $d['object'];
      $payload = $obj['payload'];
      $payload = json_decode($payload, true);
      echo($GOLD.'['.$DARK_BLUE.'id'.$obj['peer_id'].$GOLD.'|'.$DARK_BLUE.$obj['from_id'].$GOLD.'] '.$GREEN.$obj['text'].' 
');
      $peer_id = $obj['peer_id'];
      $user_id = $obj['from_id'];
      if($obj['text'] == 'помощь' || $obj['text'] == 'Помощь'){
      	$this->sendMessage($obj['peer_id'], "Привет! Мои команды:\nТест - проверить работоспособность бота\nПовтори [фраза] - бот повторит то что вы скажете");
      }
      if($obj['text'] == 'тест' || $obj['text'] == 'Тест'){
      	$this->sendMessage($peer_id, "Я работаю!");
      }
      $msg = explode(' ', $obj['text'], 2);
      if($msg[0] == 'повтори' || $msg[0] == 'Повтори'){
      	if(!empty($msg[1])){
      	    return $this->sendMessage($peer_id, "Ты написал: ". $msg[1]);
      	}else{
      	    return $this->sendMessage($peer_id, "Ты не написал что должен повторить бот!");
      	}
      }
    }
  }
  public function decode($url){
    return json_decode(file_get_contents($url),1);
  }
}
 ?>
