<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
      <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
      <link href="http://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
   </head>
   <body>
      <table border="0" cellspacing="0" cellpadding="0" style="margin: 20px auto;background-color: #fff;font-family: poppins;border-radius: 5px;border: 1px solid #ddd;width: 50em;box-shadow: 0.7px 0.7px 10px 2px rgb(214, 214, 214) !important;font-size: 14px;">
         <tbody>
            <tr>
            <td style="padding: 2.2em 4em;">
                  <table cellpadding="0" cellspacing="0" width="100%">
                     <tbody>
                        <tr>
                           <td align="left"><img src="<?php echo logo_url();?>"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td style="padding: 4em;padding-bottom: 0px;">
                  <p style="text-align: center;color:#5e7282;margin-bottom: 1.5em;">Dear <span style="font-weight: 600;">{{$ADMIN_NAME}},</span></p>
                  <br>
                  <p style="text-align: center;color:#5e7282;margin: 0;font-size: 22px;font-weight: bolder;">This is to notify you that the {{$name}} has successfully purchased {{$credits}} credits on your Bouncee application.</p>
                  <br>
                  <p style="text-align: center;color:#ff0000;margin: 0;font-size: 22px;font-weight: bolder;">Now you need to add the {{$balance_credits}} credits in your {{$PLATFORM}} API.</p>
               </td>
           
            <tr>
               <td>
                  <p style="text-align: center;color:#5e7282;margin: .5em 0;margin-top: 2rem;">Thank You!</p>
                  <p style="text-align: center;color:#5e7282;margin: 1.5px;font-weight: bolder;"><b>bouncee</b></p>
               </td>
            </tr>
         </tbody>
      </table>
   </body>
</html>

