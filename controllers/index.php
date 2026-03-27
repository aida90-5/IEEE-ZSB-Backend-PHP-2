<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
            <?php 
                 echo "Hello, world";
            ?>
        </h1>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
            <?php 
                 echo "Hello, ". "world";
            ?>
        </h1>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
            <?php 
                $greeting = "Hello";

                 echo $greeting. " world";
            ?>
        </h1>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
            <?php 
                $greeting = "Hello";
                echo "$greeting world";
            ?>
        </h1>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
            <?php 
                $greeting = "Hello";
                echo '$greeting world';
            ?>
        </h1>
    </body>
</html>

 <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h1>
           You have read "Dark Matter."  
        </h1>
    </body>
</html>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
           <style>
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
           </style>
    </head>
 
    <body>
        <?php 
          $name = "dark Matter";
        ?>
        <h1>
           You have read  "<?php $name ;?>."
        </h1>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
           <style>
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
           </style>
    </head>
 
    <body>
        <?php 
          $name = "dark Matter";
          $read = true;
            if($read){
              $message = "You have read $name" ; 
            }else{
              $message = "You have not read $name" ; 

            }
        ?>
        <h1>
           You have read  <?php echo $message ; ?>
        </h1>
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
           <style>
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
           </style>
    </head>
 
    <body>
        <?php 
          $name = "dark Matter";
          $read = true;
            if($read){
              $message = "You have read $name" ; 
            }else{
              $message = "You have not read $name" ; 

            }
        ?>
        <h1>
        <?php echo $message;?>
        <? $message ?>
        </h1>
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            "Do Andriods Dream of electric sheep ",
            "The langoliars", 
            "Hail Msry"
          ]
        ?>

        <ul>
        <?php foreach($books as $book )>
        echo "<Li> . $book .</Li>";
        </ul>
        
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            "Do Andriods Dream of electric sheep ",
            "The langoliars", 
            "Hail Msry"
          ]
        ?>

        <ul>
        <?php foreach($books as $book ):?>
        {
         echo "<Li><div>$book</div></Li>"
       }
        </ul>
        
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            "Do Andriods Dream of electric sheep ",
            "The langoliars", 
            "Hail Msry"
          ]
        ?>

        <ul>
        <?php foreach($books as $book ):?>
      <Li>Hello There</Li>
      <?php> endforeach </php>
        </ul>
        
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            "Do Andriods Dream of electric sheep ",
            "The langoliars", 
            "Hail Msry"
          ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            ["Do Andriods Dream of electric sheep "
            ' http:example.com '
            ],[
            "The langoliars", 
          ' http:example1.com '
            ],[
            "Hail Msry",
          ' http:example2.com '
            ]
          ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            [
                'name' -> "Do Andriods Dream of electric sheep "
            'PurchaseURL'-> 'http:example.com '
            ]
          ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        
    </body>

    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            [
                'name' -> "Do Andriods Dream of electric sheep "
            'PurchaseURL'-> 'http:example.com '
            ]
          ]
        ?>
        <p>
            <?= $books['name']>
        </p>
        <ul>
        <?php foreach($books as $book ):?>
      <Li><?= $books ?> </Li>
      <?php> endforeach </php>
        </ul>
    </body>


    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            [
                'name' -> "Do Andriods Dream of electric sheep "
            'PurchaseURL'-> 'http:example.com '
            ]
          ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        <ul>
        <?php foreach($books as $book ):?>
      <Li><?= $books ?> </Li>
      <?php> endforeach </php>
        </ul>
    </body>

    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
          $book =[
            [
                'name' -> "Do Andriods Dream of electric sheep "
            'PurchaseURL'-> 'http:example.com '
            ]
          ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        <ul>
        <?php foreach($books as $book ):?>
      <Li>
      <a href="<?= $book ['purchaseURL]">
      <?= $books ['name']
      </a>
      ?> </Li>
      <?php> endforeach </php>
        </ul>
    </body>

      [
                    'name' => 'Project Hail Merry',
                    'author'=> 'Andy Weir',
                    'releaseYear' => 2021,
                    'purchaseUrl' => 'https://example.com',
                ],
                
                [

                    'name' => 'The Martian',
                    'author'=> 'Andy Weir',
                    'releaseYear' => 2011,
                    'purchaseUrl' => 'https://example.com',
                ],

            ];
            
    
            function filterByAuthor($books, $author) {
                $filteredBooks = [];
            
                foreach ($books as $book) {
                    if ($book['author'] = $author) {
                        $filteredBooks[] = $book;
                    }
                }
            
                return $filteredBooks;
            }
            
           
            
         ?>

        <ul>
            <?php foreach ($filterByAuthor($books, 'Andy Weir') as $book) : ?>
                    <li>
                        <a href="<?= $book['purchaseUrl'] ?>">
                            <?= $book['name']; ?> (<?= $book['releaseYear'] ?>)-BY <?= $book['author'] ?>
                        </a>
                    </li>
            
            <?php endforeach; ?>

        </ul>
    

</body>