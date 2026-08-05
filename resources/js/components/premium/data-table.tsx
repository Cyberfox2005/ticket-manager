import React from 'react';
import { cn } from '@/lib/utils';
import { ChevronDown, MoreHorizontal, Search, Filter } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface DataTableProps {
    columns: { header: string; accessor: string }[];
    data: any[];
    title: string;
}

export function DataTable({ columns, data, title }: DataTableProps) {
    return (
        <div className="flex flex-col gap-4">
            <div className="flex items-center justify-between">
                <h3 className="text-xl font-bold text-foreground">{title}</h3>
                <div className="flex items-center gap-2">
                    <div className="relative w-64">
                        <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                        <Input placeholder="Search..." className="bg-white/5 pl-9 border-white/10" />
                    </div>
                    <Button variant="outline" size="sm" className="gap-2 border-white/10 bg-white/5">
                        <Filter className="size-4" /> Filters
                    </Button>
                </div>
            </div>

            <div className="overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md">
                <table className="w-full text-left">
                    <thead>
                        <tr className="border-b border-white/10 bg-white/5 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            {columns.map((col) => (
                                <th key={col.accessor} className="px-6 py-4">{col.header}</th>
                            ))}
                            <th className="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                        {data.map((row, i) => (
                            <tr key={i} className="transition-colors hover:bg-white/10 group">
                                {columns.map((col) => (
                                    <td key={col.accessor} className="px-6 py-4 text-sm text-muted-foreground group-hover:text-foreground">
                                        {row[col.accessor]}
                                    </td>
                                ))}
                                <td className="px-6 py-4 text-right">
                                    <Button variant="ghost" size="icon" className="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <MoreHorizontal className="size-4" />
                                    </Button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <div className="flex items-center justify-between border-t border-white/10 p-4 bg-white/5">
                    <p className="text-xs text-muted-foreground">Showing {data.length} results</p>
                    <div className="flex gap-2">
                        <Button variant="outline" size="sm" className="border-white/10">Previous</Button>
                        <Button variant="outline" size="sm" className="border-white/10">Next</Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
