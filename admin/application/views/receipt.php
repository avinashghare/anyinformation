<html lang="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Starter Template for Bootstrap 3.3.4</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <style>
        body {
            padding-top: 50px;
        }
        
        .starter-template {
            padding: 40px 15px;
            text-align: center;
        }
    </style>

    <!--[if IE]>
        <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
    .details p {
        font-size: 14px;
        margin:15px 0;
    }
    
    .details span {
        font-size: 18px;
    }
    .sign p{
        font-size: 20px;
        margin-top:15%;
        text-align: center;
    }
</style>

<body>

    <div class="container">
        <div class="starter-template">
            <h2>Receipt</h2>
        </div>
        <div class="row">
            <div class="col-md-6 details ">
                <div>
                    <p><span>LISTING:</span><?php echo $table->listingname;?></p>
                </div>
                <div>
                    <p> <span>USER:</span><?php echo $table->firstname." ".$table->lastname;?></p>
                </div>
<!--
                <div>
                    <p> <span>PAYMENT TYPE:</span>Demo text Demo text Demo text Demo text Demo text Demo text Demo text</p>
                </div>
-->
                <div>
                    <p> <span>CREDITS:</span><?php echo $table->credits;?></p>
                </div>
            </div>
            <div class="col-md-6 details">
                <div>
                    <p><span>AMOUNT:</span><?php echo $table->amount;?></p>
                </div>
                <div>
                    <p> <span>START DATE:</span><?php echo $table->startdate;?></p>
                </div>
                <div>
                    <p> <span>END DATE:</span><?php echo $table->enddate;?></p>
                </div>
            </div>
        </div>
        <div class="row sign">
            <div class="col-md-6">
                <p>YOUR SIGNATURE:<br><?php echo $table->firstname." ".$table->lastname;?></p>
            </div>
            <div class="col-md-6">
                <p>PAYED TO:<br><?php echo $table->payedtofirstname." ".$table->payedtolastname;?></p>
            </div>
            
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script>
        $(window).load(function () {
            window.print();
        });
    </script>
</body>

</html>