<?php
session_start();
define('title','聊天室');

if(!isset($_SESSION['nickname']))
{
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <script type="text/javascript" src="jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="jquery.qqFace.js"></script>
    <style>
        *{margin:0px; padding:0px;}
        #content{width:80%; margin:0px auto;}
        
    </style>
</head>
<body>
    <div id="content">
         <h1 style="margin:20px auto;text-align:center;">
            <?php echo title;?> welcome ! <?php echo $_SESSION['nickname'];?>
            
           
        </h1>
        <div style="border:1px solid red;height:300px; overflow:scroll; margin-bottom:20px;">
            <table id="message_table">
                
            </table>
        </div>
        
      
            发送聊天内容:
            <div id="content_message" style="width:500px;height:200px; border:1px solid red;" contenteditable="true">
                
            </div>
            
             <div class="emotion">表情</div>
            <input type="button" id="send_message" value="发送" />
     
    </div>
    
    <div style="border:1px solid blue; width:182px;height:100px;position:absolute; top:85px;right:0px;overflow:scroll;">
        <dl id="online">
            <dt>在线好友</dt>
         
        </dl>
    </div>
    
    <script>
    var nickname = "<?php echo $_SESSION['nickname'];?>";
    //websocket
    var exampleSocket = new WebSocket("ws://192.168.33.10:9501");
	  exampleSocket.onopen = function (event) {
		  var default_information = {};
		  default_information['type'] = 'connect';
		  default_information['nickname'] = nickname;
		  default_information['content_message'] = '';
		  var content = JSON.stringify(default_information);
		  exampleSocket.send(content); 
	  };


	  exampleSocket.onmessage = function (event) {
		  //php 端收到信息之后再返回  
		 var obj = eval('(' + event.data + ')');
		
		if(obj.type == 'send_message')
		{
			 $('#message_table').append("<tr><td>"+obj.message+"</td></tr>");
		}

		if(obj.type == 'online')
		{
			$('#online').children('dd').remove();

			for(var i=0;i<obj.nicknames.length;i++){

				$('#online').append("<dd style='color:red;'>"+obj.nicknames[i]+"</dd>")
			}
			
		}	   
	 }

		


		//打开页面直接发送一条信息
		

		  $(function(){
	 	    	$('.emotion').qqFace({
		    		id : 'facebox', //表情盒子的ID
		    		assign:'content_message', //给那个控件赋值
		    		path:'face/'	//表情存放的路径
		    	}); 

		    	$('#send_message').click(function(){

		            var content_message = $('#content_message').html();

		            if(content_message == "")
		            {
		          	  alert('内容不能为空');
		                return false;   			
		            }else
		            {
		            	var information = {};
		            	information['type'] = 'message';
						information['nickname'] = nickname;
						information['content_message'] = content_message;
						
		               var content = JSON.stringify(information);
		            	
		      		  exampleSocket.send(content); 
		      		  
		      		  $('#content_message').html(''); 		 
		      		  	 
		            }
		          });
		    	
		    });
		 
		 
	
	    
    	
    	
    </script>
</body>
</html>