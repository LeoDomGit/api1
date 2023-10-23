<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="background: #fff !important; margin: 0 auto; margin-top: 30px; width: 90%; font-size: 14px; color: #333333; border: 1px solid #e1e1e1;">
	<div style="margin: 0 auto; width: 90%">
		<div style="margin-bottom: 25px;">
            <h3 style="font-size: 20px;">Thông báo hỗ trợ</h3>
            <p>Mail được gửi từ hệ thống</p>
            <h4 style="font-size: 18px;">
                Người dùng : {{$mailData['name']}}
            </h4>
            <h4 style="font-size: 18px;">
                Email: {{$mailData['email']}}
            </h4>
            <h4 style="font-size: 18px;">
                Số điện thoại : {{$mailData['phone']}}
            </h4>
            <h4 style="font-size: 18px;">
                Thông tin : {{$mailData['subject']}}
            </h4>
            
            <h4 style="font-size: 18px;">
                Nội dung cần hỗ trợ : {{$mailData['content']}}
            </h4>
            
		</div>
		<p>Thân gửi,</p>
	</div>	
	<div style="width: 100%; margin-top: 50px; height: 100px;overflow: hidden;">
    	<img  src="https://img.freepik.com/free-vector/hand-painted-watercolor-pastel-sky-background_23-2148902771.jpg" alt="logo" class="img-responsive" style="width: 100%">
  	</div>
</body>
</html>