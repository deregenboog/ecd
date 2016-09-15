<?php

class AttachmentsController extends AppController
{
	public $name = 'Attachments';

	public function download($id = null, $version = null, $download = true)
	{
		if ($id == null) {
			return 'wrong path!';
		}
		Configure :: write('debug', 0);
		$this->autoRender = false;

		$file_record = $this->Attachment->find('first', array(
			'conditions' => array(
				'Attachment.id' => $id,
			),
				));

		if (!$file_record) {
			
			$this->flashError(__('The file you requested could not be found in the database.', true));
			$this->redirect('/');
			
		}

		$dir_name = $file_record['Attachment']['dirname'];
		$filename = $file_record['Attachment']['basename'];
		
		if (empty($version)) {
			$file_path = MEDIA.$dir_name.'/'.$filename;
		} else {

			$config = Configure::read('Media.filter.image');
			
			if (isset($config[$version]['convert'])) {
				$ext = explode('/', $config[$version]['convert']);
				$name = explode('.', $filename);
				$filename = $name[0].'.'.$ext[1];
			}
			
			$file_path = MEDIA.'filter/'.$version.'/'.$dir_name.'/'.
					$filename;
		}

		$size = filesize($file_path);
		$type = mime_content_type($file_path); 

		header("Pragma: public");
		header("Expires: 0");
		header("Accept-Ranges: bytes");
		header("Connection: close");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: $type");
		header("Content-Disposition-type: attachment");
		
		if ($download) {
			header("Content-Disposition: attachment; filename=\"$filename\"");
		}
		
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$size);

		$this->_readfile_chunked($file_path);
		
	}

	public function _readfile_chunked($path, $retbytes = true)
	{

		while (ob_get_level() > 0) {
			ob_end_flush();
		}
		ob_implicit_flush(1);

		$chunksize = 8 * 1024; 
		$buffer = '';
		$cnt = 0;
		$handle = fopen($path, 'rb');
		
		if ($handle === false) {
			return false;
		}
		
		while (!feof($handle)) {
			
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			
			if ($retbytes) {
				$cnt += strlen($buffer);
			}
		}
		
		$status = fclose($handle);
		
		if ($retbytes && $status) {
			return $cnt; 
		}
		return $status;
	}

	public function delete($attachmentId)
	{
		$att = $this->Attachment->read(null, $attachmentId);

		if (! $att) {
			$this->flashError(__('Invalid attachment id.', true));
			$this->redirect('/');
			return;
		}

		if ($att['Attachment']['user_id'] != $this->Session->read('Auth.Medewerker.id')) {
			$this->flashError(__('Cannot delete attachment, you are not the uploader.', true));
			$this->redirect('/');
			return;
		}

		$this->Attachment->set('is_active', false);
		$this->Attachment->save();

		$this->flash(__('Document deleted.', true));
		$model = $att['Attachment']['model'];
		
		$controller =Inflector::Pluralize($model);
		$this->redirect($this->referer());
	}
}
