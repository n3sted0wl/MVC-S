<div id="dev-info-page-heading">
    <div id="dev-info-page-welcome-container">
        <h1>MVC-S</h1>
        <p>
            This is the MVC-S framework, designed after the
            ASP.NET framework. The MVC part is pretty standard
            and the S stands for "Service". This framework is 
            largely asynchronous. 
        </p>
        <p>
            Upon a page request, the application checks the url
            resource path and calls the Controller that renders
            the appropriate View, using classes defined as 
            Models. If the url requests a Service, it performs
            the operation and returns a JSON object with the 
            information about the operation.
        </p>
    </div>
    <div id="dev-info-page-navigation-container">
        <div class="link-button" data-url="/UnitTests">
            Unit Tests
        </div>
        <div class="link-button" data-url="/Dev">
            Dev Page
        </div>
    </div>
</div>
<hr />
<div id="dev-info-page-body">
    <div class='information-section'>
        <h3>Adding a new page</h3>
        <ol>
            <li>
                <h6>Choose a page name and view</h6>
                <p>
                    Seriously, you're gonna have to come up with a
                    meainingful yet managemable name for the page
                    as well as the view that will be programmatically
                    managed by the controller.
                </p>
            </li>
            <li>
                <h6>Set up the Controller class</h6>
                <p>
                    Create a class with the name of the view and make
                    it extend the base <span class="code">Controller</span>
                    class. 
                </p>
            </li>
            <li>
                <h6>Set up the View file</h6>
                <p>
                    This file will contain the markup shown when the
                    controller is called. It must be named the same as
                    the view that it renders. For example, the DevInfo
                    view is saved in the DevInfo.php file located in 
                    the /application/views folder.
                </p>
            </li>
            <li>
                <h6>Set up Navigation</h6>
                <p>
                    Go into the /application/configurations/site.json file
                    and add an entry in the navigation tree for your new page.
                    It should be self-explanatory how things are laid out...
                </p>
            </li>
            <li>
                <h6>Set up Routing</h6>
                <p>
                    This is <em>tehcnically</em> the last thing you really
                    need set up in order to get the page working. If you don't
                    care about styling, scripting, authentication, etc., 
                    you're golden at this point.
                </p>
                <p>
                    Go into the /Routes.php file and add an entry at the top for
                    your new view. You can copy-paste from one of the others.
                </p>
                <p>
                    For example: 
                    <span class="code">Route::Set("DevInfo", function() { 
                    DevInfo::RenderView("DevInfo"); });</span>. The first 
                    parameter in the <span class="code">Set()</span> function 
                    is the url at which a user can navigate to the view. The 
                    parameter in the <span class="code">RenderView()</span>
                    function call is the name of the view. Also, use the 
                    name of the view as the class name that calls the 
                    <span class="code">RenderView()</span> function.
                </p>
            </li>
            <li>
                <h6>Set up styling and scripting</h6>
                <p>
                    Create .css and .js files in the /static/css and
                    /static/js folders to contain the styling and scripting
                    associated with your specific view.
                </p>
            </li>
            <li>
                <h6>Set up Authentication</h6>
                <p>
                    I don't know how this works yet...
                </p>
            </li>
            <li>
                <h6>Page Configuration</h6>
                <p>Setting up information</p>
            </li>
        </ol>
    </div>
    <div class='information-section'>
        <h3>Creating Unit Tests</h3>
        <p>
            Add this information
        </p>
    </div>
    <div class='information-section'>
        <h3>Navigation Details</h3>
        <p>
            Add this information
        </p>
    </div>
    <div class='information-section'>
        <h3>Data Provider</h3>
        <p>
            Add this information
        </p>
    </div>
    <div class='information-section'>
        <h3>Page Configuration</h3>
        <p>
            Add this information
        </p>
    </div>
</div>