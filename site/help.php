<?php
echo "<h1 class='title'>Help</h1>";
?>
<script>
  $(function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  });
  </script>
  <script>
  $(document).ready(function() {

  /* This is basic - uses default settings */
  
  $("a.images").fancybox();
  
  });</script>
<section class="help">
<article>
  <p>For assistance with any aspect of the Chartpad site, please take a look at the sections below.</p>
<div id="accordion">
  <h3>Create a Project</h3>
  <div>
    <p>On logging into Chartpad you will be presented with the home screen. On this screen you have a variety of options for managing projects.</p>
    <p>To create a project hit the Add Project button where you will be shown this screen:</p>
    <p><a class="images" href="help/img/addproject.png">Add a Project</a></p>
    <p>On this screen type in your project start and end dates and then select add to create your project</p>
  </div>
  <h3>Create A Task</h3>
  <div>
    <p>Once you have created your project you will be taken back to the home screen.<br/> To add a task select the add task button where you will be shown the following screen:</p>
    <p><a class="images" href="help/img/addtasks.png">Add a task</a></p>
    <p>Fill in this form with the task details taking extra care to select the correct project for the task. <br/> On selecting the project you will be shown the task parent and child options. This is where you can link your task to preceeding tasks in the project should they rely on previous milestones</p>
  </div>
  <h3>Viewing a Project</h3>
  <div>
    <p>Viewing a project allows you to change the project options and view the charts for that project.</p>
    <p>Select view projects from the home screen where you will be shown a list of all your projects.</p>
    <p><a class="images" href="help/img/viewprojects.png">View a Project</a></p>
    <p>Clicking either GANTT, PERT or WBT will take you to the chart screen of that project.</p>
    <p>Selecting Add will allow you to add another task for that project.</p>
    <p>Selecting Edit will allow you to change the project name and start and end date.</p>
    <p>Selecting Settings will allow you to adjust individual chart customisation settings for your project.</p> 
  </div>
  <h3>Viewing Tasks</h3>
  <div>
    <p>The View Tasks option from the home screen allows you to view tasks for a selected project in one handy list</p>
    <p><a class="images" href="help/img/viewtasks.png">View Project Tasks</a></p>
    <p>Here you can add, edit or remove tasks for a project from one handy screen</p>
  </div>
  <h3>Creating and Saving a chart</h3>
  <div>
    <p>One you have added all the tasks required for your project you are ready to create and view the charts for that project.</p>
    <p>Go to the View projects section of the site and select one of the chart options.</p>
    <p>You will be taken to the chart for that project, in the case of the example the PERT chart.</p>
    <p><a class="images" href="help/img/charts.png">Chart Screen</a></p>
    <p>For the PERT and WBT charts you can customise the layouts of the charts. Arrange tasks in the correct oder by using drag and drop.</p>
    <p>Hitting the save button will save the layout of the chart for later. Selecting the Cog icon will provide customisation options for that chart</p>
    <p>Finally to download the chart select one of the file type options along the top. For instance select JPG to download a JPG of your chart.</p>
  </div>
  <h3>Support Desk</h3>
  <div>
    <p>The support desk section allows you to raise support desk queries to our support team, for any bugs or problems you may encounter that are not covered by the FAQ or Help Center</p>
    <p><a class="images" href="help/img/tickets.png">Support Center</a></p>
    <p>Here you can view, update or close your outstanding tickets or raise a new ticket by selecting the new ticket button.</p>
    <p>Tickets can have screenshots attached to assist in reporting bugs.</p>
  </div>
</div>
</article>
</section>