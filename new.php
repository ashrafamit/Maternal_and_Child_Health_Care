<?php
session_start();
$page_title = "Reminder Page";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
date_default_timezone_set("Asia/Dhaka");

?>
<div class="container mb-5" id="refresh-div">
    <div class="justify-content-center">
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Token No</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php

            $find_dates_to_send_reminder = "SELECT * from reminder WHERE  rdate > CURRENT_TIMESTAMP";
            $find_dates_to_send_reminder_run = mysqli_query($con, $find_dates_to_send_reminder);

            while ($result = mysqli_fetch_array($find_dates_to_send_reminder_run)) {

                $uName = $result['name'];
                $uEmail = $result['email'];
                $uMobile = $result['phone'];
                $uToken = $result['verify_token'];
                $uDate = $result['rdate'];
                $uStatus = $result['r_status'];

                echo '<tr>';
                echo '<td>' . $uName . '</td>';
                echo '<td>' . $uMobile . '</td>';
                echo '<td>' . $uEmail . '</td>';
                echo '<td>' . $uToken . '</td>';
                echo '<td>' . $uDate . '</td>';
                echo '<td>' . $uStatus . '</td>';

                echo '</tr>';
            }


            ?>
        </table>

        <div class="justify-content-center" align=center>
            <form method="POST">
                <div class="col-lg-6">
                    <input type="submit" name="reminderBtn" value="Reminder" class="btn btn-outline-info w-100">
                </div>
            </form>
        </div>
    </div>
</div>


<?php

if (isset($_POST['reminderBtn'])) {
    include('phpMailer.php');
    $send_reminder_to = "SELECT * from reminder WHERE  CURRENT_TIMESTAMP > DATE_SUB(rdate , interval 1 day)  and r_status = 0";
    $send_reminder_to_run = mysqli_query($con, $send_reminder_to);


    while ($res = mysqli_fetch_array($send_reminder_to_run)) {

        $uName = $res['name'];
        $uEmail = $res['email'];
        $uMobile = $res['phone'];
        $uToken = $res['verify_token'];
        $uDate = $res['rdate'];
        $uStatus = $res['r_status'];

        sendemail_verify($uName, $uEmail, $uToken);
        $update_query = "UPDATE reminder SET r_status=1 WHERE rdate='$uDate' LIMIT 1";
        $update_query_run = mysqli_query($con, $update_query);

        echo "<script>refreshDiv();</script>";
    }
}
include('./includes/footer.php');
?>