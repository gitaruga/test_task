<body>
	<div id="header">
		<div class="w1000">
			<div id="top_menu" class="p20">
				<menu>
					<?
					foreach ($top_menu as $k => $i) {
						$class = $i['active'] ? ' class="active"' : '';
						?>
						<li<?=$class?>><a href="<?=$i['url']?>"><?=$i['title']?></a></li>
						<?
					}
					?>
				</menu>
				<div id="title">Учет товаров на складе</div>
			</div>
			<div class="clr"></div>
			<div id="edit_line" class="p20">
				<?=$EDIT_LINE?>
			</div>
		</div>
	</div>
	<div id="main_wrapper" >
		<div class="w1000">
			<div class="p20">
