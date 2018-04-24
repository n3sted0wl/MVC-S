INTRODUCTION
    This is a project framework for creating PHP applications,
    provided with classes to deal with Data Access, Authentication,
    DataBound controls, and some other stuff.

FRAMEWORK
    This is an MVC framework for building PHP applications. 
    The Models, Views, and Controllers are found in /application.

    In addition to the Models, Views, and Controllers, I want to add a 
    Services aspect to the framework. These are going to be accessible
    in a REST-style API by requesting them through the URL. For
    instance, requesting /services/getUserStatus with parameters will 
    return an object with properties of the requested user.

    With the addition of the Servicees, I like to call this framework
    "MVC-S"

FILE STRUCTURE
    - /                 root folder with index.php and .htaccess
    - /application      code for MVC-S and configuraiton files
    - /static           assets, resources, stylesheets, scripts

NAVIGATION
- Routes
    Routes.php calls the Controller of a requested view by parsing
    the url (which is re-written by the .htaccess file). 
    The url determines which View the Controller will load.
- Pages
    Navigation is done through routing. When a URL is requested, if 
    it doesn't not navigate to the static folder, the resource path
    is used to call the Controller class that will generate the View
    and execute requested operations. 
- Services
    If the request is made for a service, the routing will execute 
    that service and return an object with the requested data.

CONFIGURATION
    Configuration settings are stored in JSON files in 
    /application/configurations. The global configuration file is 
    site.json. 
    When the controller loads a view, it will look for a configuration 
    file with the same name as the view and override the global settings,
    if a view-specific configuration file is found.
    So to add/override global configurations for a given view, add a 
    .json file to the /application/configurations folder with the name of
    the view.

STYLING/SCRIPTING
    Scripts and stylesheets are saved in the /static folder. When the 
    Controller loads a view, it will look in the js and css folders for 
    files with the same name as the view. If they exist, the Controller 
    will generate the link and reference tags to them.

3RD-PARTY CLIENT-SIDE LIBRARIES
    These area loaded from the /static/resources folder. The Controller
    will check what Directories are present and load generate the <head>
    tag links and references to the stylesheets and scripts found there.
    The following libraries are currently being used (04/24/2018)
    - Scripting: Scripting is dones using jQuery and jQuery UI.
    - Styling: Bootstrap

DEVELOPMENT
    There are two pages included in the framework: 
    - Dev : a Sandbox page
    - UnitTests : a page for running and reviewing unit UnitTests
        The UnitTests controller has an array of test cases that
        have a name, description, and anonymous function body.
        The View loops through the array and executes each unit
        test, displaying the results.
        At the moment, this is all done synchronously. At some point,
        I want to make it async so you can run each one individually.