<?php include('header_dashboard.php'); ?>
<?php include('session.php'); ?>
<?php $get_id = $_GET['id']; ?>
<?php $unit_id = isset($_GET['unit_id']) ? $_GET['unit_id'] : null; ?>

<?php 
if (isset($_GET['delete_unit_id'])) {
    $delete_unit_id = $_GET['delete_unit_id'];
    $stmt = $conn->prepare("DELETE FROM class_subject_overview WHERE class_subject_overview_id = ?");
    $stmt->bind_param("i", $delete_unit_id);
    $stmt->execute();
    $stmt->close();
    header("Location: subject_overview.php?id=$get_id");
    exit();
}
?>

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
                                <a href="add_subject_overview.php?id=<?php echo $get_id; ?>" class="btn btn-success"><i class="icon-plus-sign"></i> Add Subject Overview</a>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <h3>Units</h3>
                                <ul class="list-group">
                                <?php 
                                $stmt = $conn->prepare("SELECT * FROM class_subject_overview WHERE teacher_class_id = ?");
                                $stmt->bind_param("i", $get_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                while ($row = $result->fetch_assoc()) { 
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="subject_overview.php?id=<?php echo $get_id; ?>&unit_id=<?php echo $row['class_subject_overview_id']; ?>"><?php echo htmlspecialchars($row['unit_title']); ?></a>
                                        <span>
                                                          <div>
                                                <a href="edit_subject_overview.php?id=<?php echo $get_id; ?>&subject_id=<?php echo $row['class_subject_overview_id']; ?>" class="btn btn-info btn-mini"><i class="icon-pencil"></i> Edit</a>
                                                <a href="subject_overview.php?id=<?php echo $get_id; ?>&delete_unit_id=<?php echo $row['class_subject_overview_id']; ?>" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure you want to delete this unit?');"><i class="icon-trash"></i> Delete</a>
                                            </div>
                                        </span>
                                    </li>
                                <?php 
                                } 
                                $stmt->close();
                                ?>
                                </ul>
                                <?php
                                if ($unit_id) {
                                    $stmt = $conn->prepare("SELECT * FROM class_subject_overview WHERE class_subject_overview_id = ?");
                                    $stmt->bind_param("i", $unit_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $unit_row = $result->fetch_assoc();
                                    $stmt->close();
                                    ?>
                                    <h3><?php echo htmlspecialchars($unit_row['unit_title']); ?></h3>
                                    <p><?php echo nl2br(htmlspecialchars($unit_row['unit_content'])); ?></p>
                                    <a href="subject_overview.php?id=<?php echo $get_id; ?>" class="btn btn-info">Back to Units</a>
                                    <?php
                                } else {
                                    echo '<div class="alert alert-info">Select a unit to view its content.</div>';
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
