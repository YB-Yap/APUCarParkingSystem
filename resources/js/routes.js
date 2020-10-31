import Dashboard from "./pages/Dashboard";
import ParkingStatus from "./pages/ParkingStatus";
import CarParkStatus from "./pages/CarParkStatus";
import More from "./pages/More";
import ParkingGate from "./pages/ParkingGate";

export const routes = [
    {
        path: "/",
        name: "index",
        redirect: "/dashboard",
    },
    {
        path: "/dashboard",
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
    {
        path: "/more",
        name: "more",
        component: More
    },
    {
        path: "/parking-gate",
        name: "parking_gate",
        component: ParkingGate
    },
];

