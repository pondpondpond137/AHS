<?php

    include_once('functions.php');
    $update_data = new DB_con();
    if (isset($_POST['update'])) {
        // ปีงบประมาณ
        $project_id = $_GET['id'];
        $year_project = $_POST['year_project'];
        $nameProject_th = $_POST['nameProject_th'];
        $nameProject_en = $_POST['nameProject_en'];
        
        // ประเภทการวิจัย
        $type_research = $_POST['type_research'];

        // ภาควิชา
        $department = $_POST['department'];

        // ความสอดคล้อง
        $relevance = $_POST['relevance'];
        if ($relevance == 'อื่นๆ'){
            $relevance = $_POST['relevance-other'];
        }
        // debug($relevance);

        // การนำไปประยุกต์ใช้
        $applied = $_POST['applied'];
        if ($applied == 'อื่นๆ'){
            $applied = $_POST['applied-other'];
        }
        
        $ethic_relate = $_POST['ethic_relate'];
        
        // debug($ethic_relate);
        if ($ethic_relate == '0') {
            $process_ethic = '0';
            $type_ethic = 'ไม่มี';
            $number_project = 'ไม่มี';
            $number_cer = 'ไม่มี';
            $date_done = 'ไม่มี';
        }
        else if ($ethic_relate == 'เกี่ยวข้อง') {

            // ประเภทของจริยธรรม
            $type_ethic = $_POST['type_ethic'];
            $process_ethic = $_POST['process_ethic'];

            if ($process_ethic == 'กำลังดำเนินการยื่นข้อเสนอขอใบรับรอง') {
                // echo "ดำเนิน";
                $number_project = NULL;
                $number_cer = NULL;
                $date_done = NULL;
            }
            else if ($process_ethic == 'ดำเนินการเสร็จแล้ว') {
                // echo "ดำเนินแล้ว";
                $number_project = $_POST['number_project'];
                $number_cer = $_POST['number_cer'];
                $date_done = $_POST['date_done'];
            }
        }
        else if ($ethic_relate == 'ไม่เกี่ยวข้อง'){
            $ethic_relate = 'ไม่เกี่ยวข้อง';
            $process_ethic = '0';
            $type_ethic = 'ไม่มี';
            $number_project = 'ไม่มี';
            $number_cer = 'ไม่มี';
            $date_done = 'ไม่มี';
        }

        // show($relevance);

        $sql = $update_data->update($year_project, $nameProject_th, $nameProject_en, $type_research, $department, $relevance, $applied, $ethic_relate, $type_ethic, $process_ethic, $number_project, $number_cer, $date_done, $project_id);

        if ($sql) {
            echo "<script>alert('Updated Successfully!');</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            echo "<script>alert('Something went wrong! Please try again!');</script>";
            echo "<script>window.location.href='update.php'</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        li {
            margin-left:50px;
        }
    </style>

</head>
<body>
    <div class="container mt-3">
        <a href="index.php" class="btn btn-primary mb-3">ย้อนกลับ</a>
        <div class="card">
            <div class="card-body">
                <h1 class="text-center">งบประมาณรายได้คณะสหเวชศาสตร์ มหาวิทยาลัยนเรศวร</h1>
                <form method="POST" id='main-form' onload="set_all()">
        <?php
        
            $project_id = $_GET['id'];
            $update_project = new DB_con();
            $sql = $update_project->fetchonerecord($project_id);
            while ($row = mysqli_fetch_array($sql)) {
            
        
        ?>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">ประจำปีงบประมาณ</span>
                            <input type="number" class="form-control" aria-describedby="addon-wrapping" name="year_project" value="<?php echo $row['year_project']; ?>">
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">ชื่อโครงการวิจัย</span>
                            <input type="text" class="form-control" placeholder="ภาษาไทย" aria-label="ภาษาไทย" aria-describedby="addon-wrapping" name="nameProject_th" value="<?php echo $row['nameProject_th']; ?>">
                        </div>
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">ชื่อโครงการวิจัย</span>
                            <input type="text" class="form-control" placeholder="ภาษาอังกฤษ" aria-label="ภาษาอังกฤษ" aria-describedby="addon-wrapping" name="nameProject_en" value="<?php echo $row['nameProject_en']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <p>
                            <u>ส่วน ก</u> : <span style="padding-left:20px;"> ลักษณะโครงการวิจัย</span>
                        </p>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">1. ประเภทการวิจัย</span>
                            <?php

                                $a = $row['typeProject'];
                                $b = 0;
                                if($a == 'พื้นฐาน'){
                                    $b = 1;
                                }
                                else if ($a == 'ประยุกต์') {
                                    $b = 2;
                                }
                                else if ($a == 'ทดลองและพัฒนา'){
                                    $b = 3;
                                }
                            ?>
                            <select class="form-select" name="type_research">
                                <option value='พื้นฐาน' <?php if($b == 1){echo 'selected';} ?>>พื้นฐาน</option>
                                <option value='ประยุกต์' <?php if($b == 2){echo 'selected';} ?>>ประยุกต์</option>
                                <option value='ทดลองและพัฒนา' <?php if($b == 3){echo 'selected';} ?>>ทดลองและพัฒนา</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap col">
                        <span class="input-group-text" id="addon-wrapping">2. ภาควิชา</span>
                        <?php

                                $a = $row['department'];
                                $b = 0;
                                if($a == 'เทคนิคการแพทย์'){
                                    $b = 1;
                                }
                                else if ($a == 'เทคโนโลยีหัวใจและทรวงอก') {
                                    $b = 2;
                                }
                                else if ($a == 'รังสีเทคนิค'){
                                    $b = 3;
                                }
                                else if ($a == 'กายภาพบำบัด'){
                                    $b = 4;
                                }
                                else if ($a == 'ทัศนมาตรศาสตร์'){
                                    $b = 5;
                                }
                            ?>
                        <select class="form-select" name="department">
                                <option value='เทคนิคการแพทย์' <?php if($b == 1){echo 'selected';} ?>>เทคนิคการแพทย์</option>
                                <option value='เทคโนโลยีหัวใจและทรวงอก' <?php if($b == 2){echo 'selected';} ?>>เทคโนโลยีหัวใจและทรวงอก</option>
                                <option value='รังสีเทคนิค' <?php if($b == 3){echo 'selected';} ?>>รังสีเทคนิค</option>
                                <option value='กายภาพบำบัด' <?php if($b == 4){echo 'selected';} ?>>กายภาพบำบัด</option>
                                <option value='ทัศนมาตรศาสตร์' <?php if($b == 5){echo 'selected';} ?>>ทัศนมาตรศาสตร์</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">3. ระบุความสอดคล้องของโครงการวิจัย</span>
                            <?php

                                $a = $row['relevance'];
                                if ($a == '0') {
                                    $b = 0;
                                }
                                else if($a == 'สังคมสูงวัย'){
                                    $b = 1;
                                }
                                else if ($a == 'นวัตกรรม') {
                                    $b = 2;
                                }
                                else if ($a == 'ไม่เกี่ยวข้องกับสังคมสูงวัยและนวัตกรรม'){
                                    $b = 3;
                                }
                                else{
                                    $b = 4;
                                }
                            ?>
                            <select class="form-select" id="relevance" onchange="other_show_relevance()" name="relevance">
                                <option id="non-value-3" value="0" <?php if($b == 0){echo 'selected';} ?> >---</option>
                                <option value='สังคมสูงวัย' <?php if($b == 1){echo 'selected';} ?>>สังคมสูงวัย</option>
                                <option value='นวัตกรรม' <?php if($b == 2){echo 'selected';} ?>>นวัตกรรม</option>
                                <option value='ไม่เกี่ยวข้องกับสังคมสูงวัย' <?php if($b == 3){echo 'selected';} ?>>ไม่เกี่ยวข้องกับสังคมสูงวัยและนวัตกรรม</option>
                                <option value='อื่นๆ' <?php if($b == 4){echo 'selected';} ?>>อื่นๆ</option>
                            </select>
                            <input type="text" class="form-control" <?php if($b != 4){echo "disabled='true'";} ?> id="other-relevance" name="relevance-other" value="<?php if($b == 4){echo $row['relevance'];} ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col">
                            <span class="input-group-text" id="addon-wrapping">4. ระบุการนำผลงานวิจัยไปใช้ประโยชน์</span></span>
                            <?php

                                $a = $row['applied'];
                                if ($a == '0') {
                                    $b = 0;
                                }
                                else if($a == 'เชิงพาณิชย์'){
                                    $b = 1;
                                }
                                else if ($a == 'เชิงสาธารณะ') {
                                    $b = 2;
                                }
                                else{
                                    $b = 3;
                                }
                            ?>
                            <select class="form-select" id="applied" onchange="other_show_applied()" name="applied">
                                <option id="non-value-4" value="0" <?php if($b == 0){echo 'selected';} ?> >---</option>
                                <option value='เชิงพาณิชย์' <?php if($b == 1){echo 'selected';} ?>>เชิงพาณิชย์</option>
                                <option value='เชิงสาธารณะ' <?php if($b == 2){echo 'selected';} ?>>เชิงสาธารณะ</option>
                                <option value='อื่นๆ' <?php if($b == 3){echo 'selected';} ?>>อื่นๆ</option>
                            </select>
                            <input type="text" class="form-control" <?php if($b != 3){echo "disabled='true'";} ?> id="other-applied" name="applied-other" value="<?php if($b == 3){echo $row['applied'];} ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group flex-nowrap col" id="warp-relate">
                            <span class="input-group-text" id="addon-wrapping">5. จริยธรรมในการวิจัย</span>
                            <?php
                            
                                $a = $row['ethic_relate'];
                                if ($a == '0'){
                                    $b = 0;
                                }
                                else if ($a == 'เกี่ยวข้อง') {
                                    $b = 1;
                                }
                                else if ($a == 'ไม่เกี่ยวข้อง') {
                                    $b = 2;
                                }
                            
                            ?>
                            <select class="form-select" id="ethics" onchange="toggle_menu_ethics()" name="ethic_relate">
                                <option value="0" <?php if($b == 0){echo 'selected';}?>>---</option>
                                <option value='เกี่ยวข้อง' <?php if($b == 1){echo 'selected';}?>>เกี่ยวข้อง*</option>
                                <option value='ไม่เกี่ยวข้อง' <?php if($b == 2){echo 'selected';}?>>ไม่เกี่ยวข้อง</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <!-- พื้นที่แสดงผลตอนเลือก เกี่ยวข้องกับไม่เกี่ยวข้อง -->
                    <div class="row mb-3" id="relate" <?php if($b != 1){echo 'hidden';} ?>>
                        <div class="input-group flex-nowrap col">

                        <?php

                            $a_1 = $row['type_relate'];
                            if ($a_1 == 'ไม่มี'){
                                $b_1 = 0;
                            }
                            else if ($a_1 == 'จริยธรรมในการวิจัยมนุษย์'){
                                $b_1 = 1;                                
                            }
                            else if ($a_1 == 'จรรยาบรรณการใช้สัตว์ทดลอง'){
                                $b_1 = 2;
                            }
                            else if ($a_1 == 'ความปลอดภัยทางชีวภาพ'){
                                $b_1 = 3;
                            }
                        
                        ?>
                            <select class="form-select" name="type_ethic">
                                <option value='จริยธรรมในการวิจัยมนุษย์' <?php if($b_1 == 1){echo 'selected';} ?>>จริยธรรมในการวิจัยมนุษย์</option>
                                <option value='จรรยาบรรณการใช้สัตว์ทดลอง' <?php if($b_1 == 2){echo 'selected';} ?>>จรรยาบรรณการใช้สัตว์ทดลอง</option>
                                <option value='ความปลอดภัยทางชีวภาพ' <?php if($b_1 == 3){echo 'selected';} ?>>ความปลอดภัยทางชีวภาพ</option>
                            </select>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="row mb-3" id="relate" <?php if($b != 1){echo 'hidden';} ?>>
                        <div class="col-3">
                            <?php
                            
                                $a_1 = $row['process_ethic'];
                                if ($a_1 == 'กำลังดำเนินการยื่นข้อเสนอขอใบรับรอง'){
                                    $b_1 = 0;
                                }
                                else if ($a_1 == 'ดำเนินการเสร็จแล้ว'){
                                    $b_1 = 1;
                                }
                            
                            ?>

                            <span class="input-group-text">
                                <input type="radio" style="margin-right:5px" id="radio_s1" onchange="selected_processing()" name="process_ethic" value='กำลังดำเนินการยื่นข้อเสนอขอใบรับรอง' <?php if($b_1 == 0){echo 'checked';} ?>>
                                กำลังดำเนินการยื่นข้อเสนอขอใบรับรอง
                            </span>
                        </div>
                        <div class="col input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">หมายเลขโครงการวิจัย</span>
                            <input type="text" class="form-control" <?php if($b_1 != 1){echo 'disabled';}else{echo 'value="'.$row['number_project'].'"';} ?> id="text_s2" name="number_project">
                        </div>
                    </div>
                    <div class="row mb-3" id="relate" <?php if($b != 1){echo 'hidden';} ?>>
                        <div class="col input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"><input type="radio" style="margin-right:5px" id="radio_s2" onclick="selected_processed()" id="process_ethic_2" name="process_ethic" value='ดำเนินการเสร็จแล้ว'  <?php if($b_1 == 1){echo 'checked';} ?>>ใบรับรองเลขที่</span>
                            <input type="text" class="form-control" id="text_s2" <?php if($b_1 != 1){echo 'disabled';} ?> <?php if($b_1 != 1){echo 'disabled';}else{echo 'value="'.$row['number_cer'].'"';} ?> name="number_cer">
                        </div>
                        <div class="col input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">ลงวันที่</span>
                            <input type="date" class="form-control" <?php if($b_1 != 1){echo 'disabled';} ?> <?php if($b_1 != 1){echo 'disabled';}else{echo 'value="'.$row['date_done'].'"';} ?> id="text_s2" name="date_done">
                        </div>
                    </div>
                    <div class="row mb-3" id="unrelate" <?php if($b != 2){echo 'hidden';} ?>>
                        <p style="margin-top:20px;">ซึ่งข้าพเจ้า ได้รับทราบ และเข้าใจ เกี่ยวกับ</p>
                        <ul>
                            <li>ประกาศมหาวิทยาลัยนเรศวร เรื่อง คุณสมบัติ หลักเกณฑ์โครงการวิจัยที่ต้องขอรับรองจริยธรรมการวิจัยในมนุษย์</li>
                            <li>ประกาศมหาวิทยาลัยนเรศวร เรื่อง การกำหนดวิธีดำเนินงานตามแนวทางปฏิบัติเพื่อความปลอดภัยทางชีวภาพในกำกับของคณะกรรมการเพื่อความปลอดภัย</li>
                            <li>จรรยาบรรณการใช้สัตว์เพื่องานทางวิทยาศาสตร์ ของสภาวิจัยแห่งชาติ</li>
                        </ul>
                        <p>ทั้งนี้ ข้าพเจ้าขอยืนยันว่าโครงการวิจัยที่ขอส่งรับการสนับสนุนทุนวิจัยนี้ ไม่มีความเกี่ยวข้องกับประกาศดดังกล่าวหากภายหลังพบว่าข้อเสนอโครงการวิจัยนี้ มีความเกี่ยวข้อง ข้าพเจ้าจะรับผิดชอบต่อลกระทบที่จะเกิดขึ้นแต่เพียงผู้เดียว</p>
                    </div>
                    <?php } ?>
                    <button type="submit" name="update" class="btn btn-success">บันทึกข้อมูล</button>
                </form>
            </div>
        </div>
    </div>
    

<script src="script_2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>