<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Signature;
use TCPDF;
use \setasign\Fpdi\TcpdfFpdi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class DocumentController extends Controller
{
    // Hiển thị form tải file PDF
    public function uploadForm()
    {
        return view('upload_form');
    }
    public function uploadForm_sig()
    {
        return view('upload_form_sig');
    }
    // Lưu file PDF vào thư mục lưu trữ
    public function upload(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240', // PDF, dung lượng tối đa 10MB
        ]);

        $fileName = time() . '.' . $request->pdf_file->extension();
        $request->pdf_file->storeAs('document', $fileName);
        $request->pdf_file->storeAs('document_signed', $fileName);
        
        // Lưu thông tin file vào cơ sở dữ liệu
        $document = new Document;
        $document->title = $request->title;
        $document->file_path0 = 'app/document/' . $fileName;
        $document->file_path = 'app/document_signed/' . $fileName;
        $document->save();


        return redirect()->route('upload.form')->with('success', 'PDF uploaded successfully.');
    }
    public function upload_sig(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:png,jpg|max:10240', // PDF, dung lượng tối đa 10MB
        ]);

        $fileName = time() . '.' . $request->pdf_file->extension();
        $request->pdf_file->storeAs('signature', $fileName);
        
        // Lưu thông tin file vào cơ sở dữ liệu
        $user = User::findOrFail(auth()->user()->id);
        $user->file_path = 'app/signature/' . $fileName;
        $user->save();
        // $sig = new Signature();
        // $sig->file_path = 'app/signature/' . $fileName;
        // $sig->user_id = auth()->user()->id;
        // $sig->save();


        return redirect()->route('upload_sig.form')->with('success', 'PDF uploaded successfully.');
    }

    // Hiển thị danh sách văn bản đã tải lên
    public function index()
    {
        // $documents = Document::all();
        // $documents = Document::with('users')->get();
        $documents = Document::with(['users' => function ($query) {
            $query->select('users.id', 'users.name', 'signatures.created_at');
        }])->get();
        return view('documents', compact('documents'));
    }

    // Ký văn bản
    // public function sign($id)
    // {
    //     $document = Document::findOrFail($id);

    //     // Tạo một instance của TCPDF
    //     $pdf = new TCPDF();
    //     $pdf->AddPage();
    //     // $pdf->SetFont('Arial', '', 12);
    //     // Đọc nội dung từ file PDF đã có sẵn
    //     $filePath = storage_path($document->file_path);
    //     if (file_exists($filePath)) {
    //         $existingPdfContent = file_get_contents($filePath);
    //         // Thêm nội dung của file PDF đã có sẵn vào file mới
    //         $pdf->writeHTML($existingPdfContent, true, false, true, false, '');
    //         // Thực hiện các thao tác khác nếu cần, ví dụ: thêm chữ ký
    //         // Ký văn bản bằng hình ảnh chữ ký
    //         $signatureImage = storage_path('app/signature/chu-ky-wikici.png'); // Đường dẫn đến hình ảnh chữ ký
    //         $pdf->Image($signatureImage, 100, 200, 50, 20, 'PNG');
    //         $pdf->Output($filePath, 'F');

    //     } else {
    //         echo "khong co file";
    //     }

    //     // // Lưu PDF chứa chữ ký vào thư mục storage
    //     // $fileName = 'document_' . $id . '.pdf';
    //     // $pdf->Output(storage_path('app/document_signature/' . $fileName), 'F');

    //     // // // Cập nhật tên tệp vào cơ sở dữ liệu
    //     // $document->file_path = 'app/document_signature/' . $fileName;
    //     // $document->save();

    //     return redirect()->route('documents.index')->with('success', 'Document signed successfully.');
    // }
    public function sign($id)
    {
        $count = DB::table("signatures")->select()->where("document_id",$id)->get()->count();

        $document = Document::findOrFail($id);
        $filePath = storage_path($document->file_path);
        $user = User::findOrFail(auth()->user()->id);
        $signature = new Signature();
        // Tạo một instance của FPDI
        $pdf = new TcpdfFpdi();

        // Mở file PDF
        $pageCount = $pdf->setSourceFile($filePath);
        // $pdf->SetFont('Arial', '', 12);
        // Đọc nội dung từ file PDF đã có sẵn
        if (file_exists($filePath)) {
            // Mở file PDF
            $pageCount = $pdf->setSourceFile($filePath);
            // Thêm các trang của file PDF đã mở vào tài liệu mới
            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                // Thêm một trang vào tài liệu mới
                $pdf->AddPage();

                // Nhúng nội dung của trang PDF đã mở vào trang mới
                $templateId = $pdf->importPage($pageNumber);
                $pdf->useTemplate($templateId);
            }
            $signatureImage = storage_path($user->file_path); // Đường dẫn đến hình ảnh chữ ký
   
            $pdf->Image($signatureImage, 100, 200 + $count*10, 50, 10, 'PNG');
            $pdf->Output($filePath, 'F');


            // Cập nhật chữ ký vào cơ sở dữ liệu
            $signature->user_id = auth()->user()->id;
            $signature->document_id = $document->id;
            $signature->save();
            $document->is_signature = true;
            $document->save();
            $pdf->Output($filePath, 'I'); // Hiển thị tài liệu PDF trong trình duyệt
        }


        return redirect()->route('documents.index')->with('success', 'Đã ký.');
    }

    // Hiển thị văn bản đã ký
    public function view($id, $path0 = 0)
    {
        $document = Document::findOrFail($id);
        if ($path0) {
            $filePath = storage_path($document->file_path0);
        } else {
            $filePath = storage_path($document->file_path);
        }
        return response()->file($filePath);
    }

    public function viewDetail($id)
    {
        $documents = Document::all();
        
        return view('document_detail', compact('documents'));
    }
}
