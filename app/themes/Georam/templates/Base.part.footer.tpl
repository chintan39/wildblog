        </div> <!-- /content -->

{if not $isHomepage}
        <hr class="noscreen" />
        
        <!-- Sidebar -->
        <div id="aside">

            <!-- News -->                    
            <h4 id="aside-title">{tg}Contact{/tg}</h4>

            <div class="aside-in">

                {include file='part.allPagesMenus' package=Basic menuName='side_menu' ulClass='sidebox'}
		
            </div> <!-- /aside-in -->
   
        <hr class="noscreen" />

            <!-- News -->                    
            <h4 id="aside-title">{tg}Contact{/tg}</h4>

            <div class="aside-in">
                <div class="aside-box">

                {include file='part.shortContact' package=Basic}

                </div> <!-- /aside-box -->
            </div> <!-- /aside-in -->
   
        </div> <!-- /aside -->
{/if}

    </div> <!-- /cols -->
    
    <hr class="noscreen" /> 

    <!-- Header -->
    <div id="header">

        <!-- Your logo -->
        <h2 id="logo"><a href="{$base}"><img src="{$projectMedia}/logo.png" alt="website name" title="website name" /><!--Your <span>website</span> name--></a></h2>
        <hr class="noscreen" />        

        <!-- Your slogan -->
        <div id="slogan">{tg}Your slogan here{/tg}</div>
        <hr class="noscreen" />        
        
    </div> <!-- /header -->

    <!-- Navigation -->
    <div id="nav">
    
		{include file='part.allPagesMenus' package=Basic menuName='top_menu' ulClass='box'}
        
    <hr class="noscreen" /> 
    </div> <!-- /nav -->

    <!-- Footer -->
    <div id="footer">

        <!-- Do you want remove this backlinks? Look at www.nuviotemplates.com/payment.php -->            
        <p class="f-right"><a href="http://www.nuviotemplates.com/">Free web templates</a> by <a href="http://www.nuvio.cz/">Nuvio</a>, sponsored by <a href="http://www.lekynainternetu.cz/" title="Léky na internetu.cz">Leky</a></p>
        <!-- Do you want remove this backlinks? Look at www.nuviotemplates.com/payment.php -->
        
        {include file='part.wwFooter' sep=' '}

    </div> <!-- /footer -->

</div> <!-- /main -->

{include file='part.htmlAreas' package=Basic}

{include file='part.adminBox' }

{include file='Base.part.footer' }

