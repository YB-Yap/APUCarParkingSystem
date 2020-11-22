import Dashboard from "./pages/Dashboard";
import ParkingStatus from "./pages/ParkingStatus";
import More from "./pages/More";
import Simulator from "./pages/Simulator";
import Subscription from "./pages/Subscription";
import AdminDashboard from "./pages/Admin/Dashboard";
import AdminMore from "./pages/Admin/More";

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
    {
        path: "/admin/",
        name: "admin_index",
        redirect: "/admin/dashboard",
    },
    {
        path: "/admin/dashboard",
        name: "admin_dashboard",
        component: AdminDashboard
    },
    {
        path: "/admin/more",
        name: "admin_more",
        props: true,
        component: AdminMore
    },
];

