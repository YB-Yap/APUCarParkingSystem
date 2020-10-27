import Dashboard from "./pages/Dashboard";
import ParkingStatus from "./pages/ParkingStatus";
import CarParkStatus from "./pages/CarParkStatus";

export const routes = [
    {
        path: "/",
        name: "dashboard",
        component: Dashboard
    },
    {
        path: "/parking-status",
        name: "parking_status",
        component: ParkingStatus
    },
    {
        path: "/car-park-status",
        name: "car_park_status",
        component: CarParkStatus
    },
];

