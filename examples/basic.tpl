<!-- @basic -->
	basic <br>{b}<br>
<!-- @basic -->

<!-- @code -->
<?php
	foreach(range(1, 10) as $val){
		echo $val . '<br>';
	}
?>
<!-- @code -->

<!-- @dot -->
	<!-- @a -->1<br>
		<!-- @b -->2<br>
			<!-- @c -->3<br>{e}
				<!-- @d -->4<br>{e}
				<!-- @d -->
			<!-- @c -->
		<!-- @b -->
	<!-- @a -->
<!-- @dot -->

<!-- @redefine -->
	<!-- @z -->1<br><!-- @z -->
	<!-- @z -->2<br><!-- @z -->
	<!-- @z -->3<br><!-- @z -->
<!-- @redefine -->

<!-- @multi-nested -->
	<!-- @a1 -->1<br>
		<!-- @b -->2{e}<br>
			<!-- @c -->3<br>
				<!-- @d -->4{e}<br>
				<!-- @d -->
			<!-- @c -->
		<!-- @b -->
	<!-- @a1 -->

	<!-- @a2 -->5<br>
		<!-- @b -->6{e}<br>
			<!-- @c -->7<br>
				<!-- @d -->8{e}<br>
				<!-- @d -->
			<!-- @c -->
		<!-- @b -->
	<!-- @a2 -->
<!-- @multi-nested -->

<!-- @nest -->
<h1>{text}</h1>
<!-- @nest -->

<!-- @scope -->
	<!-- @a -->{c}<!-- @a -->
	{c}
<!-- @scope -->

<!-- @script -->
	<br>
	'aaa'.replace(/a{3}/g, 'ccc');
<!-- @script -->