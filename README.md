# Email Cleanup Script for cPanel

## Problem

Managing a cPanel with a small hosting package can lead to frequent space issues. One common problem is that email accounts fill up with large emails, which need to be manually deleted. This process is time-consuming and repetitive.

## Approach

To automate the cleanup process, we use a PHP script that connects to all email accounts on the cPanel server, identifies emails larger than 1MB and older than 2 weeks, and deletes them. The script is scheduled to run regularly using a cron job.

## What the Script Does

1. Connects to the cPanel API to retrieve a list of all email accounts.
2. Connects to each email account using IMAP.
3. Searches for emails older than 2 weeks.
4. Checks the size of each email.
5. Deletes emails larger than 1MB.
6. Runs regularly via a cron job to ensure continuous cleanup.

## Setup Instructions

1. Upload the script to your server.
2. Set up a cron job to run the script regularly.

