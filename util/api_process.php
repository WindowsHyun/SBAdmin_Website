<?php
include("db_config.php");
include("define_text.php");
include("EncryptUtil.php");
include("session_data.php");

// 레벨 제한 체크
if ($user_permission <= PERMISSION_VIEWER) {
	print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
	exit();
}

// Referer 체크
if (strpos($_SERVER['HTTP_REFERER'], "index") == false) {
	print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
	exit();
}

$API = $_POST['api'];
$sql = '';

switch ($API) {
	case ADMIN_ADD_MENU:
		$order = $_POST['me_order_add'];
		$subOrder = $_POST['me_suborder_add'];
		$level = $_POST['me_permission_add'];
		$class = $_POST['me_class_add'];
		$menu = $_POST['me_name_add'];
		$icon = $_POST['me_icon_add'];
		$page = $_POST['me_href_add'];

		if (!isset($order) || !isset($subOrder) || !isset($level) || $order == "" || $subOrder == "" || $level == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		// sql 실행
		$sql = sprintf(
			"INSERT INTO `" . $mysql_database . "`.`" . $mysql_menu . "` (`me_order`, `me_suborder`, `me_permission`, `me_class`, `me_name`, `me_icon`, `me_href`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$mysqli->real_escape_string($order),
			$mysqli->real_escape_string($subOrder),
			$mysqli->real_escape_string($level),
			$mysqli->real_escape_string($class),
			$mysqli->real_escape_string($menu),
			$mysqli->real_escape_string($icon),
			$mysqli->real_escape_string($page)
		);
		break;

	case ADMIN_DEL_MENU:
		$no = $_POST['me_no'];
		$order = $_POST['me_order'];
		$suborder = $_POST['me_suborder'];
		$level = $_POST['me_permission'];
		$class = $_POST['me_class'];
		$name = $_POST['me_name'];
		$icon = $_POST['me_icon'];
		$href = $_POST['me_href'];

		if (!isset($no) || !isset($order) || !isset($suborder) || !isset($level) || !isset($name) || !isset($icon) || $no == "" || $order == "" || $suborder == "" || $level == "" || $name == "" || $icon == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		// SQL 실행
		$sql = sprintf(
			"DELETE FROM `" . $mysql_database . "`.`" . $mysql_menu . "` WHERE  `me_no`=%s;",
			$mysqli->real_escape_string($no)
		);
		break;

	case ADMIN_EDIT_MENU:
		$no = $_POST['me_no'];
		$order = $_POST['me_order'];
		$subOrder = $_POST['me_suborder'];
		$level = $_POST['me_permission'];
		$class = $_POST['me_class'];
		$name = $_POST['me_name'];
		$icon = $_POST['me_icon'];
		$page = $_POST['me_href'];

		if (!isset($no) || !isset($order) || !isset($subOrder) || !isset($level) || $no == "" || $order == "" || $subOrder == "" || $level == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		$sql = sprintf(
			"UPDATE `" . $mysql_database . "`.`" . $mysql_menu . "` SET `me_order`=%d, `me_suborder`=%d, `me_permission`=%d, `me_class`='%s', `me_name`='%s', `me_icon`='%s', `me_href`='%s' WHERE  `me_no`=%d;",
			$mysqli->real_escape_string($order),
			$mysqli->real_escape_string($subOrder),
			$mysqli->real_escape_string($level),
			$mysqli->real_escape_string($class),
			$mysqli->real_escape_string($name),
			$mysqli->real_escape_string($icon),
			$mysqli->real_escape_string($page),
			$mysqli->real_escape_string($no)
		);
		break;

	case ADMIN_ADD_MEMBER:
		$name = $_POST['member_add_name'];
		$email = $_POST['member_add_email'];
		$pwd = $_POST['member_add_pwd'];
		$permission = $_POST['member_add_permission'];
		$described = $_POST['member_add_described'];

		if (!isset($name) || !isset($email) || !isset($pwd) || !isset($permission) || $name == "" || $email == "" || $pwd == "" || $permission == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		// 해당 DB 테이블에 해당 메일이 있는지 여부를 체크 한다.
		$sql = sprintf(
			"INSERT INTO `" . $mysql_database . "`.`" . $mysql_admin_login_table . "` (`name`, `mail`, `pwd`, `permission`, `described`) VALUES ('%s', '%s', '%s', '%d', '%s')",
			$mysqli->real_escape_string($name),
			$mysqli->real_escape_string($email),
			$mysqli->real_escape_string(Encrypt($pwd, $email)),
			$mysqli->real_escape_string($permission),
			$mysqli->real_escape_string($described)
		);
		break;

	case ADMIN_DEL_MEMBER:
		$no = $_POST['member_no'];
		$name = $_POST['member_name'];
		$mail = $_POST['member_mail'];

		if (!isset($no) || !isset($name) || !isset($mail) || $name == "" || $mail == "" || $no == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		// 해당 DB 테이블에 해당 메일이 있는지 여부를 체크 한다.
		$sql = sprintf(
			"DELETE FROM `" . $mysql_database . "`.`" . $mysql_admin_login_table . "` WHERE  `no`=%s;",
			$mysqli->real_escape_string($no)
		);
		break;

	case ADMIN_EDIT_MEMBER:
		$no = $_POST['member_no'];
		$name = $_POST['member_name'];
		$mail = $_POST['member_mail'];
		if ( $_POST['member_pwd'] == '' || !isset($_POST['member_pwd'])){
			$pwd = 'NoPWD';
		}else{
			$pwd = Encrypt($_POST['member_pwd'], $mail);
		}
		$permission = $_POST['member_add_permission'];
		$described = $_POST['member_described'];

		if (!isset($no) || !isset($pwd) || !isset($permission) || !isset($described) || $no == "" || $pwd == "" || $permission == "" || $described == "") {
			print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
			exit();
		}

		if ($pwd == 'NoPWD') {
			// 비밀번호 변경을 안했을 경우 처리
			$sql = sprintf(
				"UPDATE `" . $mysql_database . "`.`" . $mysql_admin_login_table . "` SET `permission`=%d, `described`='%s' WHERE  `no`=%d;",
				$mysqli->real_escape_string($permission),
				$mysqli->real_escape_string($described),
				$mysqli->real_escape_string($no)
			);
		} else {
			// 비밀번호 변경을 할 때 처리
			$sql = sprintf(
				"UPDATE `" . $mysql_database . "`.`" . $mysql_admin_login_table . "` SET `pwd`='%s', `permission`=%d, `described`='%s' WHERE  `no`=%d;",
				$mysqli->real_escape_string($pwd),
				$mysqli->real_escape_string($permission),
				$mysqli->real_escape_string($described),
				$mysqli->real_escape_string($no)
			);
		}
		break;

		case USER_SELF_PASSWORD_CHANGE:
			// 입력 값 확인
			if (!isset($_POST['LoginID']) || !isset($_POST['Name']) || !isset($_POST['Pwd'])) {
				print "<script language=javascript> alert('비정상 적인 접근 입니다.1'); location.replace('login'); </script>";
				return 0;
			}
			// 값이 비어 있을 경우 확인
			if ($_POST['LoginID'] == "" || $_POST['Name'] == "" || $_POST['Pwd'] == "") {
				print "<script language=javascript> alert('비정상 적인 접근 입니다.2'); location.replace('login'); </script>";
				return 0;
			}
			// Referer 체크
			if (strpos($_SERVER['HTTP_REFERER'], "site") == false) {
				print "<script language=javascript> alert('비정상 적인 접근 입니다.3'); location.replace('login'); </script>";
				return 0;
			}
	
			$user_id = $_POST['LoginID'];
			$user_name = $_POST['Name'];
			$user_pw = $_POST['Pwd'];
			
			// mail로 유저 아이디 검색
			$sql = sprintf(
				"SELECT * FROM `user_table` WHERE `mail` = \"%s\" and `name` = \"%s\" ",
				$mysqli->real_escape_string($user_id),
				$mysqli->real_escape_string($user_name)
			);
			$result = $mysqli->query($sql);
			$row = mysqli_fetch_assoc($result);
	
			// token 과 해당 유저의 이메일이 다를 경우
			if (!hash_equals($row['mail'], $user_id)) {
				print "<script language=javascript> alert('비정상 적인 접근 입니다.'); location.replace('login'); </script>";
				exit();
			}
			// 유저 No 기록
			$user_no = $row['no'];
	
			// 비밀번호 변경 처리
			$hashed_password = password_hash($user_pw, PASSWORD_DEFAULT);
	
			// salt와 해시된 비밀번호를 데이터베이스에 저장
			$sql = sprintf(
				"UPDATE `" . $mysql_database . "`.`user_table` SET `pwd`= \"%s\" WHERE `no`=%s",
				$mysqli->real_escape_string($hashed_password),
				$mysqli->real_escape_string($user_no)
			);
			$result = $mysqli->query($sql);
	
			print "<script language=javascript> alert('비밀번호가 정상적으로 변경 되었습니다.'); location.replace('login'); </script>";
		break;
}

$result = $mysqli->query($sql);
if ($result == true) {
	echo "TRUE";
} else {
	print "<script language=javascript> alert('처리 실패.');</script>";
}
exit();
?>