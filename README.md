# Home Library

Welcome to my application that will help you catalog your home library collection, and make note of who/when a book has been loaned out.

## Getting Started

To run this application, run the following command in your terminal:

```git clone https://github.com/nikkiwizard/home-library```

Make sure you are in the folder with the Dockerfile and run:

```docker build -t home-library .```

Then run:

```docker run -p 80:80 -p 3306:3306 home-library```

Now you can navigate to ```localhost``` and start cataloging! Click on "Add Book" to add a book to your catalog. In the table, you can click on "Loan Book" to add information about who you are loaning the book too. The book will then appear in a Loans table. Click on "Return Book" when the book has been returned to your collection. 

## Persistent Data

Data will be lost whenever the Docker container comes down. So here are some steps to take to make sure your data stays!

Before starting your container, run the following command:

```docker volume create library_data```

Then run:

```docker run -p 80:80 -p 3306:3306 -v db_data:/var/lib/mysql -v [Your-Path-Here]:/var/www/html home-library```

## Future Features

I'm currently working on making the user interface nicer, as well as a delete book feature.
This application will also be split into two containers once we're in production. 
I'm actually really excited with where this is going! :)

## Notes for Professor Tolboom

Based on my proposal, I said I would be starting with the MySQL imagine, but to layer Apache on top of that was a pain in the neck. Most resources and tutorials online all start with separate containers, so for this part, I just ended up using a clean Ubuntu image and installing what I needed for this application. 
