import { AppLayout } from '@/layouts/app-layout';
import { GlassCard } from '@/components/premium/glass-card';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Save, Calendar, MapPin, Tag, Type } from 'lucide-react';
import { Link } from '@inertiajs/react';

export default function CreateEvent({ venues, categories }: any) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        venue_id: '',
        category_id: '',
        start_at: '',
        end_at: '',
        status: 'draft',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/ticketflow/admin/events');
    };

    return (
        <AppLayout>
            <Head title="Create Event - TicketFlow" />

            <div className="mx-auto max-w-4xl p-6 lg:p-10">
                <div className="mb-8 flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Button asChild variant="ghost" size="icon">
                            <Link href="/ticketflow/admin/events">
                                <ArrowLeft className="size-5" />
                            </Link>
                        </Button>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight text-foreground">Create New Event</h1>
                            <p className="text-muted-foreground">Fill in the details to launch your next experience.</p>
                        </div>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="grid gap-8">
                    <GlassCard className="grid gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="title" className="flex items-center gap-2"><Type className="size-4" /> Event Title</Label>
                            <Input
                                id="title"
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                placeholder="e.g. Summer Music Festival"
                                className="bg-white/5 border-white/10"
                            />
                            {errors.title && <p className="text-xs text-rose-500">{errors.title}</p>}
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="description">Description</Label>
                            <textarea
                                id="description"
                                rows={4}
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                className="w-full rounded-md border border-white/10 bg-white/5 p-3 text-sm outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Describe your event..."
                            />
                        </div>

                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="category" className="flex items-center gap-2"><Tag className="size-4" /> Category</Label>
                                <select
                                    id="category"
                                    value={data.category_id}
                                    onChange={e => setData('category_id', e.target.value)}
                                    className="w-full rounded-md border border-white/10 bg-white/5 p-2 text-sm outline-none focus:ring-2 focus:ring-primary"
                                >
                                    <option value="">Select Category</option>
                                    {categories.map((c: any) => <option key={c.id} value={c.id}>{c.name}</option>)}
                                </select>
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="venue" className="flex items-center gap-2"><MapPin className="size-4" /> Venue</Label>
                                <select
                                    id="venue"
                                    value={data.venue_id}
                                    onChange={e => setData('venue_id', e.target.value)}
                                    className="w-full rounded-md border border-white/10 bg-white/5 p-2 text-sm outline-none focus:ring-2 focus:ring-primary"
                                >
                                    <option value="">Select Venue</option>
                                    {venues.map((v: any) => <option key={v.id} value={v.id}>{v.name}</option>)}
                                </select>
                            </div>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="start_at" className="flex items-center gap-2"><Calendar className="size-4" /> Start Date & Time</Label>
                                <Input
                                    id="start_at"
                                    type="datetime-local"
                                    value={data.start_at}
                                    onChange={e => setData('start_at', e.target.value)}
                                    className="bg-white/5 border-white/10"
                                />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="end_at" className="flex items-center gap-2"><Calendar className="size-4" /> End Date & Time (Optional)</Label>
                                <Input
                                    id="end_at"
                                    type="datetime-local"
                                    value={data.end_at}
                                    onChange={e => setData('end_at', e.target.value)}
                                    className="bg-white/5 border-white/10"
                                />
                            </div>
                        </div>

                        <div className="flex justify-end gap-4 border-t border-white/10 pt-6">
                            <Button variant="outline" asChild>
                                <Link href="/ticketflow/admin/events">Cancel</Link>
                            </Button>
                            <Button disabled={processing} className="gap-2 bg-primary shadow-lg shadow-primary/20">
                                <Save className="size-4" /> {processing ? 'Creating...' : 'Create Event'}
                            </Button>
                        </div>
                    </GlassCard>
                </form>
            </div>
        </AppLayout>
    );
}
