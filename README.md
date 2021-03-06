# APU Car Parking System Using Cloud-Based Service

## Aims
The aim of proposed system is to enhance the existing car parking system in APU by implementing the system into cloud-based service.

## Objectives
- To visualise the status of car parking lot for user to check the availability of the car parking lot on the current cloud system.
- To reduce the workload of Bursary Department by implementing season parking subscription process on cloud.
- To improve the movement of entering and leaving the car parking area by visualising car parking status on cloud.
- To improve user experience of finding available car parking space without the fear of getting charged by not finding available parking space.

## Deliverables
APU Car Parking System allows **students**,
- To login and logout from the system.
- To check the availability of car parking areas.
- To check the time of entry and exit of the day.
- To check the estimated parking fee of the day.
- To check old records.
- To check the availability of season parking subscription.
- To subscribe for a monthly season parking.
- To check the subscription date and expiry date of subscribed season parking.
- To terminate season parking subscription.
- To search for empty parking space before charging when the parking area is full for 15 minutes.
  
APU Car Parking System allows **admin**,
- To login and logout from the system.
- To check the availability of car parking areas.
- To check all records.
- To check all subscription.
- To modify overall subscription capacity.
- To terminate season parking subscription.
  
APU Car Parking System is able,
- To automatically terminate expired subscriptions using time-based job scheduler.

## Installation
0. Prerequisite
```
PHP, MySQL, Node.js, Composer - https://getcomposer.org/download/
```
1. Git Clone
```
git clone https://github.com/YB-Yap/APUCarParkingSystem.git
```
2. Run normal laravel installation
```
composer install
```
3. Generate key for the app
```
php artisan key:generate
```
4. Create DB on MySQL
5. Create and seed tables
```
php artisan migrate --seed
```
6. DONE

## Change log
######  13/1/2021
- Fixed scheduler update wrong row
- Fixed `Logs` pagination 
######  16/12/2020
- Code cleanup
######  15/12/2020
- Code cleanup
######  13/12/2020
- Added tester accounts
- Updated UI
######  12/12/2020
- Updated UI
######  8/12/2020
- Added back `Admin/Subscription` `stored_id` for purchasing and terminating
- Resolved `Admin/Subscription` bar chart not refreshing after purchase or terminate
- Added disclaimer messages for `Admin/Settings`
- Changed `small` to `span` for  `Admin/Settings` input
######  7/12/2020
- Resolved `Dashboard->latest_record`, `ParkingStatus->latest_record` not getting the correct record
- Changed `Estimated Restock Date` to show under bar chart without admin submitting student's subscription
- Resolved `Admin/ViewActiveSubs` not showing subscriptions when availability is 0
- Removed `Admin/Subscription` `stored_id`
######  4/12/2020
- Updated `SubscriptionController->purchaseSubs()`
- Resolved `ParkingController` not getting correct `is_car_park_full`
- Added `SweetAlert` as gate display for `Simulator`
- Replaced package `autonumeric` with `vue-numeric`
- Updated UI [`Dashboard`, `ParkingStatus`, `Subscription`, `SubcriptionHistory`, `Admin/Dashboard`, `Admin/Subscription`, `Admin/ViewActiveSubs`]
- Resolved `Simulator` `SweetAlert`
- Resolved iOS Safari can't scroll to bottom, unable to click navbar
######  4/12/2020
- Added horizontal bar charts [`ParkingAvailability`, `SubscriptionAvailability`]
- Updated UI [`Dashboard`, `ParkingStatus`, `Subscription`]
######  3/12/2020
- Resolved `ParkingController->exitCarPark()` not getting last record
- Resolved iOS datetime compatibility `new Date(date.replace(/-/g, '/'))`
######  2/12/2020
- Replaced package `vue-autonumeric` with `autonumeric` due to Linux build error
- `Server side` Finished setup Ubuntu 20.04, Nginx 1.18.0, PHP 7.4.3, MySql 8, Composer 2.0.7, npm 6.14.4
- `Server side` Finished setup APUCarParkingSystem
- Resolved `app\helpers.php` namespace `App\Models\Config`
- Resolved `ParkingStatus` showing 07:30 when time_out is null
- Resolved `ParkingController->getRecords()` orderBy
- Updated `__references.txt`
- `Server side` Resolved MySql remote access
- `Server side` Started task scheduler
######  30/11/2020
- Fixed minor error in `ParkingCheck` command
- Minor changes to log query
- Resolved `ParkingLog` showing empty cell when time_out is null
######  29/11/2020
- Added task scheduler commands `ParkingCheck` and `SubscriptionCheck`
- Updated `ParkingController->exitCarPark()` and `ParkingController->feeCalculation()`
- Updated `ParkingLog` UI
######  28/11/2020
- Updated `Logs` UI
- Added `payment_method` enum column for `transactions` table
- Updated `ParkingController` and `SubscriptionController` for `Transaction`
- Added `Payment Method` for `Admin/SubscriptionLog` table
######  27/11/2020
- Added `Admin/ViewActiveSubs`
- Added `laravel-vue-pagination`
- Added `Logs` pages
######  26/11/2020
- Completed `Admin/Subscription` functions [`purchase`, `terminate`]
- Added `vue-chartjs`
- Added dummy blocks for `Dashboard`
######  25/11/2020
- Added `LandingPage` and `Admin/LandingPage`
- Added `getUserProfile()` for `Dashboard`
- Added `getStudentSubs()` for `Admin/Subscription`
######  24/11/2020
- Minor changes to `get-state` route
- Minor changes to `Subscription`, `Parking Status` UI
- Added `Subscription History`
- Added `Vue-Autonumeric` and `AutoNumeric` dependency
- Added `Admin/Settins`
- Completed `Settings` functions
######  23/11/2020
- Changed `.disclaimer` to `.section-child-wrapper` in app.scss
- Added parking records listing in `Parking Status`
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
