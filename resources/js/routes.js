import Dashboard from "./pages/Dashboard";
import ParkingStatus from "./pages/ParkingStatus";
import More from "./pages/More";
import Simulator from "./pages/Simulator";
import Subscription from "./pages/Subscription";

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
        path: "/more",
        name: "more",
        props: true,
        component: More
    },
    {
        path: "/simulator",
        name: "simulator",
        props: true,
        component: Simulator
    },
    {
        path: "/subscription",
        name: "subscription",
        props: true,
        component: Subscription
    },
];

