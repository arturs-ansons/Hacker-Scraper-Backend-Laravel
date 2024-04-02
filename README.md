# Hacker-news-scraper

## Overview

Hacker-news-scraper is a versatile web application designed to facilitate the following tasks:

- **Scraping Data**: Users can scrape data from external sources and insert it into the database.
- **Managing Articles**: Users can view, delete.
- **Preventing Duplicate Scraping**: The application intelligently checks if an article has been deleted before and avoids scraping it again.

## Key Features

- **Scraping Functionality**: Users can initiate the scraping process to retrieve data from external sources.
- **Article Management**: Users can manage articles, including deleting and editing existing articles.
- **Duplicate Prevention**: The application checks if an article has been previously deleted before scraping it again, preventing duplicates in the database.

## Architecture

Hacker-news-scraper is built on a microservices architecture, with two servers:

- **Laravel Server**: Handles backend logic, including data scraping, article management, and database interactions.
- **Vue.js Server**: Provides the frontend interface for users to interact with the application.

The architecture ensures scalability, modularity, and separation of concerns.

## User Interaction

1. **Scraping Data**:
    - Users initiate the scraping process through the application interface.
    - The Laravel server performs the scraping from external sources and inserts the data into the database.

2. **Managing Articles**:
    - Users can view a list of articles stored in the database.
    - They can delete articles they no longer need or edit existing articles as necessary.

3. **Duplicate Prevention**:
    - Before scraping data, the application checks if an article has been previously deleted.
    - If an article was deleted, the scraper avoids scraping it again, preventing duplicates in the database.

## Technologies Used

- **Laravel**: Backend logic, data scraping, and database interactions.
- **Vue.js**: Frontend interface for user interaction.
- **MySQL**: Database for storing articles and other application data.

Hacker-news-scraper includes comprehensive unit tests to ensure the reliability and correctness of its functionality. Tests cover areas such as:

- Data scraping
- Article management
- Duplicate prevention logic
- Console loggging 
