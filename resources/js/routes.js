import LandingPage from "./pages/LandingPage";
import AdminLandingPage from "./pages/Admin/LandingPage";
import Dashboard from "./pages/Dashboard";
import ParkingStatus from "./pages/ParkingStatus";
import More from "./pages/More";
import Simulator from "./pages/Simulator";
import Subscription from "./pages/Subscription";
import SubscriptionHistory from "./pages/SubscriptionHistory";
import AdminDashboard from "./pages/Admin/Dashboard";
import AdminSettings from "./pages/Admin/Settings";
import AdminMore from "./pages/Admin/More";

export const routes = [
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
        component: More
    },
    {
        path: "/simulator",
        name: "simulator",
        component: Simulator
    },
    {
        path: "/subscription",
        name: "subscription",
        component: Subscription
    },
    {
        path: "/subscription-history",
        name: "subscription_history",
        component: SubscriptionHistory
    },
    {
        path: "/admin/dashboard",
        name: "admin_dashboard",
        component: AdminDashboard
    },
    {
        path: "/admin/settings",
        name: "admin_settings",
        component: AdminSettings
    },
    {
        path: "/admin/more",
        name: "admin_more",
        component: AdminMore
    },
];

