<?php
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
                    <h1 class="h3 mb-0 text-gray-800">Notices</h1>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <?php if (!empty($listNotices) && count($listNotices) > 0): ?>
                        <div class="card shadow col-12">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        <?php
                                        /**
                                         * @var $noticeItem \App\Models\Notice
                                         */
                                        foreach ($listNotices as $noticeItem): ?>
                                            <tr>
                                                <th><a href="/task/<?= $noticeItem->getId(); ?>"><?= $noticeItem->getName(); ?></a></th>
                                                <th><?= $noticeItem->getStatus(); ?></th>
                                                <th><?= $noticeItem->getDateCreated(); ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

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