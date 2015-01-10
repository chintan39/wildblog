		<!-- Footer Wrapper -->
			<div id="footer-wrapper">

				<!-- Footer -->
					<div id="footer" class="container">
						<header>
							<h2>{tp}homepage footer text header{/tp}</h2>
						</header>
						<p><img src="{$base}app/themes/{$generalTheme}/images/logo_black.png"><br />{tp}homepage footer text{/tp}</p>
						<ul class="contact">
							<!--li><a href="#" class="icon fa-instagram"><span>Instagram</span></a></li-->
							<li><a href="{tp}facebook page link{/tp}" class="icon fa-facebook"><span>Facebook</span></a></li>
							<!--li><a href="#" class="icon fa-twitter"><span>Twitter</span></a></li-->
							<!--li><a href="#" class="icon fa-linkedin"><span>LinkedIn</span></a></li-->
						</ul>
					</div>

				<!-- Copyright -->
					<div id="copyright" class="container">
      {include file='Base.part.wwFooter.tpl' sep=' ' nopopuplogin=1}
					</div>

			</div>

{include file='Basic.part.htmlAreas.tpl' package=Basic}
{include file='Base.part.adminBox.tpl'}
	</body>
</html>
