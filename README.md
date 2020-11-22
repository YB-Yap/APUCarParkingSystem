# APU Car Parking System Using Cloud-Based Service

FYP iS fUn

## Deliverables
APU Car Parking System allows **students**,
- **DONE** To login and logout from the system.
- *TBT* To check the availability of car parking areas.
- *TBT* To check the time of entry and exit of the day.
- *TBT* To check the estimated parking fee of the day.
- *WIP* To check old records.
- *TBT* To check the availability of season parking subscription.
- *TBT* To subscribe for a monthly season parking.
- *TBT* To check the subscription date and expiry date of subscribed season parking.
- *TBT* To terminate season parking subscription.
- *TBT* To search for empty parking space before charging when the parking area is full for 15 minutes.
  
APU Car Parking System allows **admin**,
- **DONE** To login and logout from the system.
- *WIP* To check the availability of car parking areas.
- To check all records.
- To check all subscription.
- To modify overall subscription capacity.
- To terminate season parking subscription.
  
APU Car Parking System is able,
- To automatically terminate expired subscriptions using time-based job scheduler.

## Change log
######  22/11/2020
- Added `Admin` middleware
- Added `Admin Dashboard` for vue router
- Refactored `ParkingController->feeCalculation()`
- Added `More`, `Dashboard` for Admin site
- Added `category` column for `transactions` table
######  17/11/2020
- Added apcard balance checking for `Subscription`
- Added `Transaction` relationship for `User`
- `exitCarPark()`, `purchaseSubs()` and `terminateSubs()` will add transaction
######  15/11/2020
- Added `Subscription->estimateRestockDate()`
- Added `helpers->toJson()`
- Change parking fee calculation to `feeCalculation()`
- Resolved parking fee always max when there is no record for that day
- Added `Parking->estimateFee()`
######  14/11/2020
- Changed `Config->carpark_size` to `Config->zone_a_size` and `Config->zone_b_size`
- Updated helpers function
- Refactored `Dashboard` prop
- Added `Subscription status` in `Dashboard`
- Refactored `getCarState()` and `getSubsState()`
######  13/11/2020
- Completed `topup` logic
- Updated `Simulator`, `Subscription` UI
- Completed `Subscription` purchase logic
- Integrated `Subscription` into `exitCarPark()`
- Added `Subscription termination`
######  12/11/2020
- Added topup function for `Simulator`
- Added purchase function for `Subscription`
- Changed prop from `user_id` to `user`
- Refactored route name
- Added `center-container` css in `app.css`
######  8/11/2020
- Added `helpers->getCarParkSize()`
- Combined `Parking status` and `Car park status` vue
- Added `Subscription` page
- Added `is_expired` column to `subscriptions` table
- Added subscriptions helpers
- Added subscriptions listing, availability count
######  5/11/2020
- Completed `exitCarPark()` base logic [To be tested]
######  4/11/2020
- Changed `ParkingController->getAvailability()` to `helpers->getParkingAvailability()`
- Updated `exitCarPark()` logic
######  1/11/2020
- Minor changes to pages UI
- Added `getApcardBalance()`
- Changed parking zone to `A`/`B`
- Added current car status (in / not in car park)
- Added enter and exit `simulator`
######  31/10/2020
- Added `profile_pic_path` for user
- Changed dashboard manual grid system to `Vue-masonry`
- Added `More`, `Simulator` pages
- Added parking availability API for dashboard
######  30/10/2020
- Refactored `SeasonParking` to `Subscription`
- Updated `Subscription` table
- Added `helpers`
- Added `Config` model, table and seeder
######  29/10/2020
- Changed `/dashboard` to be the first page ['/' will be redirected to '/dashboard']
- Added `Vue-Sweetalert2`
- Added `UserController` to handle authentication [login, logout]
- Added authentication error message
- Updated `dashboard` UI [navbar, content]
- Updated `login` page UI
######  28/10/2020
- Changed app timezone to `Asia/Kuala_Lumpur`
- Added tables for migration [`users`, `parkings`, `season_parkings`, `transactions`]
- Added models for [`users`, `parkings`, `season_parkings`, `transactions`]
- Added users_table seeder [1 `admin`, 3 `students`]
- Added `user authentication`
######  27/10/2020
- Added `Vue-Router`, `MDI`
- Added dashboard page with dummy blocks and some testing components
- Resolved default page issue
######  26/10/2020
- Development begins
