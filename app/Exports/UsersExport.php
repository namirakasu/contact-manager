<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithEvents, WithColumnWidths, WithCustomStartCell, WithDrawings
{
	/** @var string */
	private $exportedAt;

	public function __construct()
	{
		$this->exportedAt = Carbon::now()->format('Y-m-d H:i:s');
	}

	public function collection()
	{
		return User::all()->map(function ($user) {
			return [
				'ID' => $user->id,
				'Name' => $user->name,
				'Email' => $user->email,
				'Gender' => $user->gender ?? '',
				'Date of Birth' => $user->dob ?? '',
				'Address' => $user->address ?? '',
				'Contact' => $user->contact !== null ? '="' . $user->contact . '"' : '',
				'Created At' => $user->created_at ? '="' . $user->created_at->format('Y-m-d H:i:s') . '"' : '',
			];
		});
	}

	public function headings(): array
	{
		return ['ID', 'Name', 'Email', 'Gender', 'Date of Birth', 'Address', 'Contact', 'Created At'];
	}

	public function startCell(): string
	{
		// Start the table at row 4 to leave room for title and timestamp
		return 'A4';
	}

	public function columnWidths(): array
	{
		// Set explicit widths so columns are fully visible
		return [
			'B' => 40, // Name
			'F' => 60, // Address
		];
	}

	public function drawings()
	{
		$possible = [
			public_path('logo.png'),
			public_path('logo.jpg'),
			public_path('logo.jpeg'),
			public_path('logo.gif'),
			base_path('public/logo.png'),
			base_path('public/logo.jpg'),
			base_path('public/logo.jpeg'),
			base_path('public/logo.gif'),
		];
		$logoPath = null;
		foreach ($possible as $candidate) {
			if (file_exists($candidate)) {
				$logoPath = $candidate;
				break;
			}
		}

		if ($logoPath) {
			$drawing = new Drawing();
			$drawing->setName('Project Logo');
			$drawing->setDescription('contact-manager');
			$drawing->setPath($logoPath);
			$drawing->setHeight(28);
			$drawing->setCoordinates('A1');
			$drawing->setOffsetX(2);
			$drawing->setOffsetY(2);
			return [$drawing];
		}

		return [];
	}

	public function registerEvents(): array
	{
		return [
			AfterSheet::class => function (AfterSheet $event) {
				$headingsCount = count($this->headings());
				$lastColumn = Coordinate::stringFromColumnIndex($headingsCount);

				// Ensure title rows are tall enough to display the logo
				$event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(34);
				$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(20);

				// Title and export timestamp
				$event->sheet->setCellValue('B1', 'contact-manager');
				$event->sheet->mergeCells("B1:{$lastColumn}1");
				$event->sheet->getDelegate()->getStyle("B1:{$lastColumn}1")->getFont()->setBold(true)->setSize(16);
				$event->sheet->getDelegate()->getStyle("B1:{$lastColumn}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

				$event->sheet->setCellValue('B2', 'Export Time: ' . $this->exportedAt);
				$event->sheet->mergeCells("B2:{$lastColumn}2");
				$event->sheet->getDelegate()->getStyle("B2:{$lastColumn}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

				// Header styling for row 4
				$headerRange = "A4:{$lastColumn}4";
				$event->sheet->getDelegate()->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
				$event->sheet->getDelegate()->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F4E78');
				$event->sheet->getDelegate()->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
				$event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(22);

				// Freeze header and enable filter
				$event->sheet->freezePane('A5');
				$event->sheet->setAutoFilter($headerRange);
			}
		];
	}
}
