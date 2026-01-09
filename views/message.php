<?php
/**
 * @var $message \App\Models\Message
 * @var $users \App\Models\Users
 */

global $db;

use App\Models\Tasks;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/template_parts/head.php' ?>

<body id="page-top" class="bg-gradient-primary">

<div id="wrapper">

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/template_parts/header.php' ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/template_parts/topnav.php' ?>

            <!-- Begin Page Content -->
            <div class="container">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">READ MESSAGE</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Message Details</div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data" name="task_form" id="task_form" autocomplete="off" action="/message/<?= $message->getId(); ?>">

                                    <div class="mb-3">
                                        <label class="small mb-1" for="txtNoticeName">Subject</label>
                                        <input class="form-control" id="txtSubject" name="subject" type="text" placeholder="Enter your subject" value="<?= $message->getSubject(); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="small mb-1" for="txtNoticeMessage">Message</label>
                                        <textarea class="form-control" rows="5" id="txtNoticeMessage" name="message"><?= $message->getMessage(); ?></textarea>
                                    </div>

                                    <!-- Form Row        -->
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="ddStatus">Status</label>
                                            <select class="form-control" id="ddStatus" name="status">
                                                <option value="UNREAD" <?php echo (($message->getStatus() === "UNREAD")? 'selected="selected"':""); ?>>UNREAD</option>
                                                <option value="READ" <?php echo (($message->getStatus() === "READ")? 'selected="selected"':""); ?>>READ</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="small mb-1" for="ddCreatedBy">Sent To</label>
                                            <select class="form-control" id="ddToUserId" name="toUserId">
                                                <option value="" selected="selected">Please Select</option>
                                                <?php
                                                /**
                                                 * @var $user \App\Models\User
                                                 */
                                                foreach ($users as $user) {
                                                    ?>
                                                    <option value="<?=$user->getId();?>" <?php echo (($message->getToUserId() === $user->getId())? 'selected="selected"':""); ?>><?= $user->getUsername(); ?></option>
                                                    <?php
                                                }
                                                ?>

                                            </select>
                                        </div>

                                    </div>
                                    <!-- Save changes button-->
                                    <button class="btn btn-primary" type="submit">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->
    </div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/template_parts/footer.php' ?>
</body>
</html>