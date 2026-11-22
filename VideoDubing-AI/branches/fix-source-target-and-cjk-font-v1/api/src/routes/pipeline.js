const express = require('express');
const PipelineService = require('../services/pipeline.service');

const router = express.Router();

router.post('/:stage', async (req, res, next) => {
  try {
    const stage = String(req.params.stage || '').toLowerCase();
    if (!PipelineService.STAGES.has(stage)) {
      return res.status(400).json({
        success: false,
        message: `Unsupported pipeline stage: ${stage}`,
        supported: [...PipelineService.STAGES],
      });
    }

    const body = req.body || {};
    const payload = body.payload || { ...body };
    delete payload.job_id;
    delete payload.url;
    delete payload.extractor;
    delete payload.type;

    const job = await PipelineService.dispatch(stage, payload, {
      job_id: body.job_id,
      url: body.url,
      extractor: body.extractor,
      type: body.type || stage,
    });

    res.status(202).json({
      success: true,
      data: {
        ...job,
        stage,
        status_url: `${req.protocol}://${req.get('host')}/api/v1/jobs/${job.id}`,
      },
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
