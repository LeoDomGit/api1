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
            <h3 style="font-size: 20px;">Thông báo lịch học và tài khoản</h3>
            <h4 style="font-size: 18px;">
                Học viên : {{$mailData['name']}}
            </h4>
            <h4 style="font-size: 18px;">
                Email đăng nhập : {{$mailData['email']}}
            </h4>
            <h4 style="font-size: 18px;">
                Mã lớp : {{$mailData['classcode']}}
            </h4>
            <h4 style="font-size: 18px;">
                Giáo viên : {{$mailData['teacher']}}
            </h4>
            <h4 style="font-size: 18px;">
               Lịch học : {{$mailData['schedule']}}
            </h4>
		</div>
		<p>Thân gửi,</p>
	</div>	
	<div style="width: 100%; margin-top: 50px; height: 100px;overflow: hidden;">
    	<img  src="https://img.freepik.com/free-vector/hand-painted-watercolor-pastel-sky-background_23-2148902771.jpg" alt="logo" class="img-responsive" style="width: 100%">
  	</div>
</body>
</html>