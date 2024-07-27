<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php 
$get_id = $_GET['id']; 
$unit_id = isset($_GET['unit_id']) ? $_GET['unit_id'] : null;
?>
<body>
    <?php include('navbar_student.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('subject_overview_link_student.php'); ?>
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
                            <div class="muted pull-left"></div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <?php 
                                if ($unit_id) {
                                    // Hiển thị nội dung bài giảng
                                    $stmt = $conn->prepare("SELECT * FROM class_subject_overview WHERE class_subject_overview_id = ?");
                                    $stmt->bind_param("i", $unit_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $unit_row = $result->fetch_assoc();
                                    $stmt->close();
                                    ?>
                                    <h3><?php echo htmlspecialchars($unit_row['unit_title']); ?></h3>
                                    <p><?php echo nl2br(htmlspecialchars($unit_row['unit_content'])); ?></p>
                                    <a href="subject_overview_student.php?id=<?php echo $get_id; ?>" class="btn btn-info">Back to Units</a>
                                    <?php
                                } else {
                                    // Hiển thị danh sách các bài giảng
                                    $stmt = $conn->prepare("SELECT * FROM class_subject_overview WHERE teacher_class_id = ?");
                                    $stmt->bind_param("i", $get_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $stmt->close();
                                    ?>
                                    <h3>Units</h3>
                                    <ul>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <li>
                                            <a href="subject_overview_student.php?id=<?php echo $get_id; ?>&unit_id=<?php echo $row['class_subject_overview_id']; ?>"><?php echo htmlspecialchars($row['unit_title']); ?></a>
                                        </li>
                                    <?php } ?>
                                    </ul>
                                    <?php
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
