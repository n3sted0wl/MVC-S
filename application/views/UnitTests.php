<div id="page-header" style="padding: 20px 0; text-align: center">
    <h2>Unit Test Results</h2>
    <div style="color: gray;">
        <p>
            This page processes the unit tests 
            defined in the controller code of this page.
        </p>
        <p>
            The code on this page is all procedural. So its
            functionality is not dependent on the accuracy of
            the class definitions it is meant to test.
        </p>
        <div id="testCountContainer">
            <span>Succeeded : </span><span id="spSuccessfulCount"></span> | 
            <span>Failed : </span><span id="spFailedCount"></span> | 
            <span>Incomplete : </span><span id="spIncompleteCount"></span>
        </div>
        <div id="btnShowSuccessfulTests" class="UnitTestButton">
            Toggle Successful Tests
        </div>
        <div id="btnExpandAllSections" class="UnitTestButton">
            Expand All
        </div>
        <div id="btnCollapseAllSections" class="UnitTestButton">
            Collapse All
        </div>
    </div>
    <hr />
</div>
<div id="unitTestContainer">
    <?php // Loop through Unit Tests and output results
        $unitTests = UnitTests::GetUnitTests();
        foreach (array_keys($unitTests) as $unitTestKey) {
            echo "<div class='unitTestRepeater' style='padding:0 50px;'>";
            echo "<h4 class='unitTestTitle' style='margin: 0;'>{$unitTestKey}</h4>";
            echo "<div>";
            echo "<div class='unitTestDescription'>Summary: "
                    .$unitTests[$unitTestKey][0]."</div>";
            echo "<div class='unitTestOutput'>";
            echo "<span style='font-weight: bold;'>Test Output:</span><br />";
            $result = call_user_func($unitTests[$unitTestKey][1]);
            echo "</div>";

            echo "<div class='unitTestResult'>Test Result: ";
            if ($result === true) {
                echo "<span class='success-message'>Succeeded<span>";
            } else if ($result === false) {
                echo "<span class='error-message'>Failed</span>";
            } else if (is_null($result)) {
                echo "<span class='incomplete-message'>Incomplete</span>";
            }
            echo "</div></div></div>";
        }
    ?>
</div>