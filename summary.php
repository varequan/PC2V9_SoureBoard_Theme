<style type="text/css">
	body{
		color:#000;
		font-family: 'Microsoft YaHei', Arial, Helvetica;
		width:100%;
		margin:0 auto;
	}
	tr:hover {
		background-color:#317082;
		color:#000;
		
	}
	tr .name{
		color:#1a5cc8;	
	}
	tr:hover  .name{
		color:orange;
		
	}
	table{
		margin-top:25px;
		border:1px;
		color:#555;
		text-align:center;
		border-collapse: collapse;
		border:1px dashed gray;
	}
	thead td{
		font-weight:bold;
		color:#000;
		background-color:#E2EBED;
	}
	th{
		color:#000;
		width:70px;
	}
	tr:nth-child(odd) {
	  background: #555;
	  background: rgba(55, 55, 55, 0.1);
	}
	tr:nth-child(even) {
	  background: #666;
	  background: rgba(69, 67, 66, 0.3);
	}
	.tried {
		background-color:#F08080;
	} 
	.solved {
		background-color:#ADFF2F;
	}
	.firstBlood{
		background-color:#8ACC26;
	}
	h1{
		color: #00FF00; 
		text-shadow: 0.1em 0.1em 0.15em #333;
	}
	#copyright{
		font-style: italic;
		color: transparent;
		background-color : black;
		text-shadow : rgba(255,255,255,0.5) 0 5px 6px, rgba(255,255,255,0.2) 1px 3px 3px;
		-webkit-background-clip : text;
	}
	a:link {  
	text-decoration: none; 
		color: #1a5cc8;
   }  
   a:visited {  
		text-decoration: none;  
		color: orange;
   }  
   a:hover {  
   text-decoration: underline;  
   color: orange;  
   }  
   a:active {  
   text-decoration: none;  
   color: orange;  
   }  
	#title{float: left;}
	#legendouter{float: right;}
	#header{height: 81px;}
 </style>
 <div id='legendouter'>
		<table id='tablelegend'>
			<tbody>
				<tr>
					<td class='color'>颜色说明：</td>
					<td class='firstBlood'>第一个成功解决|</td>
					<td class='solved'>题目成功解决|</td>
					<td class='tried'>题目解决失败</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
	if(isset($_GET["url"]))	$url = $_GET["url"];
	else $url = "./summary.html";
	$freeze = 0;
	if(($file_handle = fopen($url, "r"))){
		$fb = array (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		while (!feof($file_handle)) {
			$line = fgets($file_handle);
			if(!strncmp("<td></td><td>Submitted/1st Yes/Total Yes",$line,40)){
				$len = strlen($line)-1;
				for($i=64,$pro=0;$i<$len;){
					for(;!($line[$i]>='0'&&$line[$i]<='9')&&$i<$len;++$i);
					for(;$line[$i]!='/'&&$i<$len;++$i);
					++$i;
					if($line[$i]=='-')$fb[$pro++] = 0;
					else{
						for($num=0;$line[$i]!='/'&&$i<$len;++$i)$num = $num*10+$line[$i]-'0';
						$fb[$pro++] = $num;
					}
					for(;$line[$i]!='<'&&$i<$len;++$i);
				}
			}
		}
		fclose($file_handle);
		if($file_handle = fopen($url, "r")){
			if($freeze)echo "<font color=red size = 7>Freeze</font></br>";
			while (!feof($file_handle)) {
				$line = fgets($file_handle);	
				$len = strlen($line);
				$rank = 0;
				if(!strncmp("<HEAD>",$line,6)){
					echo $line;
					echo '<meta http-equiv="refresh" content="15" />';
				}else if(!strncmp("<td></td><td>Submitted/1st Yes/Total Yes",$line,40)){
					for($j=0,$len=$len-1;$len>0;--$len){
						if($line[$len]=='<')++$j;
						if($j==2)break;
					}
					for($i=0;$i<$len;++$i)echo $line[$i];
				}else if(!strncmp("<th>",$line,4)){
					for($j=0,$len=$len-1;$len>0;--$len){
						if($line[$len]=='<')++$j;
						if($j==2)break;
					}
					for($i=0;$i<$len;++$i)echo $line[$i];
				}else if(!strncmp("<td>",$line,4)){
				
					for($j=0,$len=$len-1;$len>0;--$len){
						if($line[$len]=='<')++$j;
						if($j==2)break;
					}
					for($j=0,$i=0;$i<$len;++$i){
						if($line[$i]=='>')++$j;
						if($line[$i]=='>'&& $j==3)echo " align=center class='name'";
						echo $line[$i];
						if($j==8)break;
					}
					$pro = 0;
					for($i = $i+1;$i<$len;++$i){
						for(;$line[$i]!='>'&&$i<$len;++$i)echo $line[$i];
						if($line[$i+1]!='0'){
							for($j=$i+1;$line[$j]!='/'&&$j+2<$len;++$j);
							if($line[$j+1]=='-' &&$line[$j+2]=='-'){
								echo ' class="tried"';
								for(;$i<$len&&$line[$i]!='/';++$i)echo $line[$i];
								for(;$i<$len&&$line[$i]!='<';++$i);
							}else if($line[$j+1]>='0' && $line[$j+1]<='9'){
								for($num=0,$j=$j+1;$line[$j]!='<';++$j)$num = $num*10+$line[$j]-'0';
								if($num == $fb[$pro]) echo ' class="firstBlood"';
								else echo ' class="solved"';
								echo ">";
								$i=$i+1;
							}
						}else {
							echo ">";
							for($i=$i+1;$i<$len&&$line[$i]!='<';++$i);
						}
						++$pro;
						for($j=0;$i<$len;++$i){
							echo $line[$i];
							if($line[$i]=='>')++$j;
							if($j==1)break;
						}				
					}
					
				}else if(!strncmp("<p>",$line,3)){
					$line = fgets($file_handle);
					$line = fgets($file_handle);
					$line = fgets($file_handle);
				}else{
					echo $line;
				}
			}
		}else echo '<meta http-equiv="refresh" content="0" />';
		fclose($file_handle);
	}else echo '<meta http-equiv="refresh" content="0" />';
	echo "Theme by:<a href='http://www.imqrw.com'>VareQuan</a>";
?>
