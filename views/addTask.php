<?php
/**
 * @var $task \App\Models\Task
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
                    <h1 class="h3 mb-0 text-gray-800">ADD NEW TASK</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Task Details</div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data" name="task_form" id="task_form" autocomplete="off" action="/addTask">

                                    <div class="mb-3">
                                        <label class="small mb-1" for="txtTaskName">Task Name</label>
                                        <input class="form-control" id="txtTaskName" name="name" type="text" placeholder="Enter your username" value="">
                                    </div>

                                    <div class="mb-3">
                                        <label class="small mb-1" for="txtTaskDescription">Task Description</label>
                                        <textarea class="form-control" rows="5" id="txtTaskDescription" name="description"></textarea>
                                    </div>

                                    <!-- Form Row        -->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (organization name)-->
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="txtDueDate">Due Date</label>
                                            <input class="form-control" id="txtDueDate" type="date" placeholder="Enter your due date" value="" name="dueDate" />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="small mb-1" for="ddStatus">Status</label>
                                            <select class="form-control" id="ddStatus" name="status">
                                                <option value="PENDING">Pending</option>
                                                <option value="DONE">Done</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (phone number)-->
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="ddCreatedBy">Created by</label>
                                            <select class="form-control" id="ddCreatedBy" name="created_by">
                                                <option value="" selected="selected">Please Select</option>
                                                <?php
                                                /**
                                                 * @var $user \App\Models\User
                                                 */
                                                foreach ($users as $user) {
                                                    ?>
                                                    <option value="<?=$user->getId();?>"><?= $user->getUsername(); ?></option>
                                                    <?php
                                                }
                                                ?>

                                            </select>

                                        </div>
                                        <!-- Form Group (birthday)-->
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="ddCreatedBy">Assigned To</label>
                                            <select class="form-control" id="ddCreatedBy" name="assigned_to">
                                                <option value="" selected="selected">Please Select</option>
                                                <?php
                                                /**
                                                 * @var $user \App\Models\User
                                                 */
                                                foreach ($users as $user) {
                                                    ?>
                                                    <option value="<?=$user->getId();?>"><?= $user->getUsername(); ?></option>
                                                    <?php
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <!-- Save changes button-->
                                    <button class="btn btn-primary" type="submit" name="addTask">Save changes</button>
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