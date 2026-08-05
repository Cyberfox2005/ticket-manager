import { AppLayout } from '@/layouts/app-layout';
import { GlassCard } from '@/components/premium/glass-card';
import { Head } from '@inertiajs/react';
import {
    Ticket,
    TrendingUp,
    Users,
    Calendar,
    DollarSign,
    ArrowUpRight,
    ArrowDownRight,
    Search,
    Filter,
    Plus
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

const stats = [
    { name: 'Total Revenue', value: '$128,430', change: '+12.5%', trending: 'up', icon: DollarSign },
    { name: 'Tickets Sold', value: '14,230', change: '+18.2%', trending: 'up', icon: Ticket },
    { name: 'Active Events', value: '24', change: '-2.4%', trending: 'down', icon: Calendar },
    { name: 'Unique Customers', value: '8,432', change: '+5.4%', trending: 'up', icon: Users },
];

const recentOrders = [
    { id: 'ORD-7234', customer: 'Alex Rivera', event: 'Summer Music Festival', amount: '$150.00', status: 'Paid', date: '2 mins ago' },
    { id: 'ORD-7235', customer: 'Sarah Chen', event: 'Tech Conference 2026', amount: '$450.00', status: 'Paid', date: '15 mins ago' },
    { id: 'ORD-7236', customer: 'Michael Ross', event: 'Broadway: Lion King', amount: '$85.00', status: 'Pending', date: '45 mins ago' },
    { id: 'ORD-7237', customer: 'Emma Wilson', event: 'Summer Music Festival', amount: '$300.00', status: 'Paid', date: '1 hour ago' },
];

export default function AdminDashboard() {
    return (
        <AppLayout>
            <Head title="TicketFlow Dashboard" />

            <div className="flex flex-col gap-8 p-6 lg:p-10">
                {/* Header */}
                <div className="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-foreground">TicketFlow Overview</h1>
                        <p className="text-muted-foreground">Manage your organization's events and sales in real-time.</p>
                    </div>
                    <div className="flex items-center gap-3">
                        <Button variant="outline" className="gap-2">
                            <Filter className="size-4" /> Filters
                        </Button>
                        <Button className="gap-2 bg-primary text-primary-foreground shadow-lg shadow-primary/20">
                            <Plus className="size-4" /> Create Event
                        </Button>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    {stats.map((stat) => (
                        <GlassCard key={stat.name} className="flex flex-col gap-4">
                            <div className="flex items-center justify-between">
                                <div className="rounded-lg bg-primary/10 p-2 text-primary">
                                    <stat.icon className="size-6" />
                                </div>
                                <div className={cn(
                                    "flex items-center gap-1 text-sm font-medium",
                                    stat.trending === 'up' ? "text-emerald-500" : "text-rose-500"
                                )}>
                                    {stat.change}
                                    {stat.trending === 'up' ? <ArrowUpRight className="size-4" /> : <ArrowDownRight className="size-4" />}
                                </div>
                            </div>
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">{stat.name}</p>
                                <p className="text-2xl font-bold">{stat.value}</p>
                            </div>
                        </GlassCard>
                    ))}
                </div>

                {/* Main Content Area */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Sales Chart Mockup */}
                    <GlassCard className="lg:col-span-2">
                        <div className="mb-6 flex items-center justify-between">
                            <h3 className="text-lg font-semibold">Sales Performance</h3>
                            <select className="bg-transparent text-sm text-muted-foreground outline-none">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>Last Year</option>
                            </select>
                        </div>
                        <div className="flex h-64 items-end gap-2 px-2">
                            {[40, 70, 45, 90, 65, 80, 55, 75, 50, 85, 60, 95].map((val, i) => (
                                <div
                                    key={i}
                                    className="relative flex-1 rounded-t-lg bg-primary/20 transition-all duration-500 hover:bg-primary/40"
                                    style={{ height: `${val}%` }}
                                >
                                    <div className="absolute inset-x-0 top-0 h-1 bg-primary blur-[2px]" />
                                </div>
                            ))}
                        </div>
                        <div className="mt-4 flex justify-between px-2 text-xs text-muted-foreground">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                            <span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                        </div>
                    </GlassCard>

                    {/* Quick Actions / Activity */}
                    <GlassCard>
                        <h3 className="mb-4 text-lg font-semibold text-foreground">Quick Actions</h3>
                        <div className="flex flex-col gap-3">
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-blue-500/20 p-2 text-blue-500"><Plus className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Add New Venue</div>
                                    <div className="text-xs text-muted-foreground">Setup seating maps and sections.</div>
                                </div>
                            </Button>
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-purple-500/20 p-2 text-purple-500"><Users className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Manage Team</div>
                                    <div className="text-xs text-muted-foreground">Invite managers and gate staff.</div>
                                </div>
                            </Button>
                            <Button variant="ghost" className="justify-start gap-3 border border-white/5 bg-white/5 py-6">
                                <div className="rounded-md bg-orange-500/20 p-2 text-orange-500"><TrendingUp className="size-4" /></div>
                                <div className="text-left">
                                    <div className="text-sm font-medium">Financial Reports</div>
                                    <div className="text-xs text-muted-foreground">Export revenue data to Excel/PDF.</div>
                                </div>
                            </Button>
                        </div>
                    </GlassCard>
                </div>

                {/* Recent Orders Table */}
                <GlassCard className="overflow-hidden p-0">
                    <div className="flex items-center justify-between border-b border-white/10 p-6">
                        <h3 className="text-lg font-semibold">Recent Ticket Orders</h3>
                        <div className="relative w-64">
                            <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input placeholder="Search orders..." className="bg-white/5 pl-9 border-white/10" />
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-white/5 text-xs uppercase tracking-wider text-muted-foreground">
                                <tr>
                                    <th className="px-6 py-4 font-medium">Order ID</th>
                                    <th className="px-6 py-4 font-medium">Customer</th>
                                    <th className="px-6 py-4 font-medium">Event</th>
                                    <th className="px-6 py-4 font-medium">Amount</th>
                                    <th className="px-6 py-4 font-medium">Status</th>
                                    <th className="px-6 py-4 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-white/5">
                                {recentOrders.map((order) => (
                                    <tr key={order.id} className="transition-colors hover:bg-white/5">
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{order.id}</td>
                                        <td className="px-6 py-4 text-sm text-muted-foreground">{order.customer}</td>
                                        <td className="px-6 py-4 text-sm text-muted-foreground">{order.event}</td>
                                        <td className="px-6 py-4 text-sm font-semibold text-foreground">{order.amount}</td>
                                        <td className="px-6 py-4 text-sm">
                                            <span className={cn(
                                                "rounded-full px-2 py-1 text-xs font-medium",
                                                order.status === 'Paid' ? "bg-emerald-500/10 text-emerald-500" : "bg-orange-500/10 text-orange-500"
                                            )}>
                                                {order.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-muted-foreground">{order.date}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="border-t border-white/10 p-4 text-center">
                        <Button variant="link" className="text-sm text-primary hover:text-primary/80">View All Orders</Button>
                    </div>
                </GlassCard>
            </div>
        </AppLayout>
    );
}

function cn(...inputs: any[]) {
    return inputs.filter(Boolean).join(' ');
}
