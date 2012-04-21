        </div> <!-- /content -->

        <hr class="noscreen" />

        <!-- Sidebar -->
        <div id="aside">

            <!-- News -->                    
            <h4 id="aside-title">{tg}Contact{/tg}</h4>

            <div class="aside-in">
                <div class="aside-box">

                {require file='part.shortContact' package=Basic}

                </div> <!-- /aside-box -->
            </div> <!-- /aside-in -->
   
        </div> <!-- /aside -->
    
    </div> <!-- /cols -->
    
    <hr class="noscreen" /> 

    <!-- Header -->
    <div id="header">

        <!-- Your logo -->
        <h2 id="logo"><a href="#"><img src="{$projectMedia}/logo.png" alt="website name" title="website name" /><!--Your <span>website</span> name--></a></h2>
        <hr class="noscreen" />        

        <!-- Your slogan -->
        <div id="slogan">{tg}Your slogan here{/tg}</div>
        <hr class="noscreen" />        
        
    </div> <!-- /header -->

    <!-- Navigation -->
    <div id="nav">
    
		{require file='part.allPagesMenus' package=Basic menuName='top_menu'}
        <ul class="box">
            <li id="nav-active"><a href="#">Homepage</a></li> <!-- Active page (highlighted) -->
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        
    <hr class="noscreen" /> 
    </div> <!-- /nav -->

    <!-- Footer -->
    <div id="footer">

        <!-- Do you want remove this backlinks? Look at www.nuviotemplates.com/payment.php -->            
        <p class="f-right"><a href="http://www.nuviotemplates.com/">Free web templates</a> by <a href="http://www.nuvio.cz/">Nuvio</a>, sponsored by <a href="http://www.lekynainternetu.cz/" title="Léky na internetu.cz">Leky</a></p>
        <!-- Do you want remove this backlinks? Look at www.nuviotemplates.com/payment.php -->
        
{tg}Powered by{/tg} <a href="http://code.google.com/p/wildblog/" title="wildblog">wildblog project</a>,
Honza Horák, <a href="http://www.wild-web.eu" title="www.wild-web.eu">wild-web.eu</a>
&copy; {$now|date_format:"%Y"} | <a href="{$base}admin/">{tg}Administration{/tg}</a>

    </div> <!-- /footer -->

</div> <!-- /main -->


{require file='part.adminBox' theme=Common}

{require file='part.footer' theme=Common}

