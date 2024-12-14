<?php 
    session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dolphin CRM</title>
   <link rel="stylesheet" href="styles.css">
</head>
<body>
   <header>
       <h1>Dolphin CRM</h1>
       <nav>
           <ul>
               <li><a href="#users">Users</a></li>
               <li><a href="#contacts">Contacts</a></li>
               <li><a href="#notes">Notes</a></li>
           </ul>
       </nav>
       <form action="index.php" method="post">
        <input type="submit" name ='logout' value="logout">
       </form>
   </header>


   <main>
       <!-- Users Section -->
       <section id="users">
           <h2>Users</h2>
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th>First Name</th>
                       <th>Last Name</th>
                       <th>Password</th>
                       <th>Email</th>
                       <th>Role</th>
                       <th>Created At</th>
                   </tr>
               </thead>
               <tbody>
                   <!-- User data dynamically inserted -->
               </tbody>
           </table>
       </section>


       <!-- Contacts Section -->
       <section id="contacts">
           <h2>Contacts</h2>
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th>Title</th>
                       <th>First Name</th>
                       <th>Last Name</th>
                       <th>Email</th>
                       <th>Telephone</th>
                       <th>Company</th>
                       <th>Type</th>
                       <th>Assigned To</th>
                       <th>Created By</th>
                       <th>Created At</th>
                       <th>Updated At</th>
                   </tr>
               </thead>
               <tbody>
                   <!-- Contact data dynamically inserted -->
               </tbody>
           </table>
       </section>


       <!-- Notes Section -->
       <section id="notes">
           <h2>Notes</h2>
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th>Contact ID</th>
                       <th>Comment</th>
                       <th>Created By</th>
                       <th>Created At</th>
                   </tr>
               </thead>
               <tbody>
                   <!-- Notes data dynamically inserted -->
               </tbody>
           </table>
       </section>
   </main>


   <footer>
       <p>&copy; 2024 Dolphin CRM</p>
   </footer>
</body>
</html>
<?php 
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>