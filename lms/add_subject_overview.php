<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php $get_id = $_GET['id']; ?>
<body>
    <?php include('navbar_teacher.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('subject_overview_link.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <!-- breadcrumb -->
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM teacher_class
                                            LEFT JOIN class ON class.class_id = teacher_class.class_id
                                            LEFT JOIN subject ON subject.subject_id = teacher_class.subject_id
                                            WHERE teacher_class_id = ?");
                    $stmt->bind_param("i", $get_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $class_row = $result->fetch_assoc();
                    $stmt->close();
                    ?>
                    <ul class="breadcrumb">
                        <li><a href="#"><?php echo htmlspecialchars($class_row['class_name']); ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><?php echo htmlspecialchars($class_row['subject_code']); ?></a> <span class="divider">/</span></li>
                        <li><a href="#"><b>Subject Overview</b></a></li>
                    </ul>
                    <!-- end breadcrumb -->

                    <!-- block -->
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-right">
                                <a href="subject_overview.php?id=<?php echo $get_id; ?>" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form class="form-horizontal" method="post">
                                    <div class="control-group">
                                        <label class="control-label" for="unitTitle">Unit Title:</label>
                                        <div class="controls">
                                            <input type="text" name="unit_title" id="unitTitle" required>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="unitContent">Unit Content:</label>
                                        <div class="controls">
                                            <textarea name="unit_content" id="unitContent" required></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button name="save" type="submit" class="btn btn-info"><i class="icon-save"></i> Save</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                if (isset($_POST['save'])){
                                    $unit_title = $_POST['unit_title'];
                                    $unit_content = $_POST['unit_content'];
                                    $stmt = $conn->prepare("INSERT INTO class_subject_overview (teacher_class_id, unit_title, unit_content) VALUES (?, ?, ?)");
                                    $stmt->bind_param("iss", $get_id, $unit_title, $unit_content);
                                    if($stmt->execute()) {
                                        echo "<script>window.location = 'subject_overview.php?id=$get_id';</script>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>
</body>
</html>
