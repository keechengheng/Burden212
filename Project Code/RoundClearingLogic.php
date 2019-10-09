<?php

#Round 1
// Check vacancies for bidded course and section - Do we clear bid table after every round? Is Bid table specific to a single round?
// Create table rows depending on vacancy of bidded course and section/bids (whichever higher)
// sort highest bid
// Only 1 bid can be the clearing price. No same price. Drop all if same.
// Check if price is at least 10

// Update bids for student (userid) if succesful, dropped, unsuccesful
// Update vacancy for course + section (use count at start num of successful bids)


#Round 2
// Same as round 1 - case 1
// If vacancy is filled. Min bid = N th bid price + 1
// If vacancy not filled. Min bid = 10
// If vacancy is filled -> get replaced by higher bid. Min bid = replaced bid + 1. Lowest bid gets pushed out alongw ith all the same lowest bid.

?>